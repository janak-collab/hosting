<?php

use App\Services\Logger;
ob_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/pdf_error.log');

use Dompdf\Dompdf;
use Dompdf\Options;

try {
    $base_dir = dirname(__FILE__);
    
    require_once $base_dir . '/includes/config.php';
    require_once $base_dir . '/includes/functions.php';
    require_once $base_dir . '/dompdf/autoload.inc.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            // Validate required fields first
            $required_fields = ['patient_name', 'dob', 'dos', 'provider_name'];
            foreach ($required_fields as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("Required field missing: " . $field);
                }
            }

            // Check if any imaging is ordered
            $imaging_fields = [
                'shoulder_xray', 'shoulder_mri',
                'hip_xray', 'hip_mri',
                'SIJ_xray', 'SIJ_mri',
                'knee_xray', 'knee_mri',
                'cervical_spine', 'thoracic_spine', 'lumbar_spine'
            ];

            $has_orders = false;
            foreach ($imaging_fields as $field) {
                if (!empty($_POST[$field])) {
                    $has_orders = true;
                    break;
                }
            }

            if (!$has_orders) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'message' => 'No imaging ordered',
                    'noPdf' => true
                ]);
                exit;
            }

            // Continue with PDF generation only if imaging was ordered
            $patient_name = filter_var($_POST['patient_name'], FILTER_SANITIZE_STRING);
            try {
                $dob = new DateTime($_POST['dob']);
                $dos = new DateTime($_POST['dos']);
            } catch (Exception $e) {
                throw new Exception("Invalid date format");
            }
            $provider_name = filter_var($_POST['provider_name'], FILTER_SANITIZE_STRING);

            $formatted_dob = $dob->format('F j, Y');
            $formatted_dos = $dos->format('F j, Y');

            // Build HTML content
            $html = '<!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Radiology Order Form</title>
                <style>
                    body { 
                        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, 
                            "Helvetica Neue", Arial, sans-serif;
                        line-height: 1.6; 
                        padding: 20px;
                        color: #212529;
                    }
            
                    .letterhead {
                        width: 100%;
                        margin-bottom: 20px;
                        font-size: 6pt;
                        line-height: 1.2;
                        border-bottom: 1px solid #000;
                        padding-bottom: 10px;
                    }
            
                    h1 {
                        font-size: 24pt;
                        text-align: center;
                        margin-bottom: 30px;
                        font-weight: normal;
                    }
            
                    h2 {
                        font-size: 18pt;
                        margin-bottom: 20px;
                        font-weight: normal;
                        border-bottom: 2px solid #007bff;
                        padding-bottom: 5px;
                    }
            
                    .patient-info {
                        margin-bottom: 30px;
                    }
            
                    /* Remove bold from labels and standardize font */
                    .patient-info-label {
                        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, 
                            "Helvetica Neue", Arial, sans-serif;
                        font-weight: normal;
                        font-size: 12pt;
                    }
            
                    /* Style for the fax report text */
                    .fax-report {
                        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, 
                            "Helvetica Neue", Arial, sans-serif;
                        font-weight: normal;
                        font-size: 12pt;
                    }
            
                    .signature-section {
                        margin-top: 50px;
                        border-top: 1px solid #ddd;
                        padding-top: 20px;
                    }
            
                    /* Add this class for standard text */
                    .standard-text {
                        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, 
                            "Helvetica Neue", Arial, sans-serif;
                        font-weight: normal;
                        font-size: 12pt;
                    }
                </style>
            </head>
            <body>';

            // Add letterhead
            $html .= '<table class="letterhead">
                <tr>
                    <td>Greater Maryland Pain Management</td>
                    <td class="center">
                        1130 Annapolis Road<br>
                        Suite 100<br>
                        Odenton, MD 21113
                    </td>
                    <td class="right">
                        Ph: (410) 672-2255<br>
                        Fax: (410) 672-2275
                    </td>
                </tr>
            </table>';

            // Add main header
            $html .= '<div class="header"><h1>Radiology Order Form</h1></div>';

            // Add patient information
            $html .= '<div class="patient-info">
                <h2>Patient Information</h2>
                <p><strong>Name:</strong> ' . htmlspecialchars($patient_name) . '</p>
                <p><strong>Date of Birth:</strong> ' . htmlspecialchars($formatted_dob) . '</p>
            </div>';

            // Start imaging orders section
            $html .= '<div class="section"><h2>Imaging Orders</h2>';
            
            $has_xray = false;
            $has_mri = false;

            // Process bilateral imaging fields
            foreach (['shoulder', 'hip', 'SIJ', 'knee'] as $part) {
                foreach (['xray', 'mri'] as $type) {
                    $field = "{$part}_{$type}";
                    if (!empty($_POST[$field])) {
                        $value = $_POST[$field];
                        $imagingType = ($type === 'xray') ? 'XR' : 'MRI';
                        
                        // Set flags for notes section
                        if ($type === 'xray') {
                            $has_xray = true;
                        } else {
                            $has_mri = true;
                        }
                        
                        // Determine side
                        if (strpos($value, 'Right') !== false) {
                            $side = 'right';
                            $displaySide = 'right';
                        } elseif (strpos($value, 'Left') !== false) {
                            $side = 'left';
                            $displaySide = 'left';
                        } elseif (strpos($value, 'Bilateral') !== false) {
                            $side = 'bilateral';
                            $displaySide = 'bilateral';
                        }
                        
                        // Get ICD10 code if available
                        $diagnosisCode = '';
                        if (isset(ICD10_CODES[$part][$side])) {
                            $diagnosisCode = sprintf(
                                '<span class="order-code">ICD-10: %s</span>', 
                                htmlspecialchars(ICD10_CODES[$part][$side])
                            );
                        }
                        
                        // Format the order display
                        $orderText = sprintf(
                            '%s %s %s',
                            $imagingType,
                            $part === 'SIJ' ? 'SIJ' : strtolower($part),
                            $displaySide
                        );
                        
                        // Add to HTML
                        $html .= sprintf(
                            '<div class="order-item">%s %s</div>',
                            htmlspecialchars($orderText),
                            $diagnosisCode
                        );
                    }
                }
            }

            // Process spine imaging
            foreach (['cervical', 'thoracic', 'lumbar'] as $level) {
                if (!empty($_POST["{$level}_spine"])) {
                    $value = $_POST["{$level}_spine"];
                    $type = (strpos($value, 'XR') !== false) ? 'xray' : 'mri';
                    
                    // Set flags for notes section
                    if ($type === 'xray') {
                        $has_xray = true;
                    } else {
                        $has_mri = true;
                    }
                    
                    // Get ICD10 code if available
                    $diagnosisCode = '';
                    if (isset(ICD10_CODES['spine'][$level][$type])) {
                        $diagnosisCode = sprintf(
                            '<span class="order-code">ICD-10: %s</span>', 
                            htmlspecialchars(ICD10_CODES['spine'][$level][$type])
                        );
                    }
                    
                    // Format the order display
                    $orderText = sprintf(
                        '%s %s spine',
                        $type === 'xray' ? 'XR' : 'MRI',
                        $level
                    );
                    
                    // Add to HTML
                    $html .= sprintf(
                        '<div class="order-item">%s %s</div>',
                        htmlspecialchars($orderText),
                        $diagnosisCode
                    );
                }
            }

            // Add notes based on imaging types
            $html .= '</div>';
            
            if ($has_xray) {
                $html .= '<p class="note">Note: X-rays are standard views.</p>';
            }
            if ($has_mri) {
                $html .= '<p class="note">Note: MRI is without contrast unless specified differently.</p>';
            }

            // Add provider signature section
            $html .= '<div class="signature-section">
                <p>Ordering clinician: ' . htmlspecialchars($provider_name) . '</p>
                <p>Date: ' . date('m/d/Y') . '</p>
                <p><strong>Fax report to: (410) 672-2275</strong></p>
            </div>';

            $html .= '</body></html>';

            // Configure DOMPDF
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', false);
            $options->set('defaultFont', 'Arial');
            
            // Create and render PDF
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Clear any output buffers
            while (ob_get_level()) {
                ob_end_clean();
            }

            // Set headers for PDF download
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment');
            header('Cache-Control: no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');

            // Output PDF
            echo $dompdf->output();

        } catch (Exception $e) {
            Logger::error('Error: ', ['error' => $e->getMessage()]);
            error_log("Stack trace: " . $e->getTraceAsString());
            
            while (ob_get_level()) {
                ob_end_clean();
            }
            
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    } else {
        http_response_code(405);
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid request method'
        ]);
    }
} catch (Exception $e) {
    Logger::error('Configuration error: ', ['error' => $e->getMessage()]);
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Server configuration error: ' . $e->getMessage()
    ]);
}
?>