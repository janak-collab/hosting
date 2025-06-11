#!/bin/bash
echo "=== GMPM Backup Verification ==="
echo "Backup Location: $(pwd)"
echo ""
echo "Files in backup:"
ls -lh
echo ""
echo "Backup sizes:"
du -sh *
echo ""
echo "Total backup size:"
du -sh .
