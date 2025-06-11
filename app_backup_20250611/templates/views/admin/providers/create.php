<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Provider - GMPM Admin</title>
    <link rel="stylesheet" href="/assets/css/form-styles.css">
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>➕ Add Provider</h1>
                <p>Create a new provider account</p>
            </div>
            
            <div class="form-content">
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Username <span class="required">*</span></label>
                            <input type="text" name="username" class="form-input" required 
                                   value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Password <span class="required">*</span></label>
                            <input type="password" name="password" class="form-input" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Full Name <span class="required">*</span></label>
                            <input type="text" name="full_name" class="form-input" required
                                   value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Title <span class="required">*</span></label>
                            <select name="title" class="form-select" required>
                                <option value="">Select title</option>
                                <option value="MD">MD</option>
                                <option value="DO">DO</option>
                                <option value="PA">PA</option>
                                <option value="NP">NP</option>
                                <option value="CRNA">CRNA</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">NPI Number</label>
                            <input type="text" name="npi_number" class="form-input"
                                   value="<?php echo htmlspecialchars($_POST['npi_number'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">License Number</label>
                            <input type="text" name="license_number" class="form-input"
                                   value="<?php echo htmlspecialchars($_POST['license_number'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Locations</label>
                        <?php foreach ($locations as $location): ?>
                            <label style="display: block; margin: 0.5rem 0;">
                                <input type="checkbox" name="locations[]" value="<?php echo $location; ?>">
                                <?php echo $location; ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="form-actions">
                        <a href="/admin/providers" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Provider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
