---
name: Bug Report
about: Report a bug or unexpected behavior in the FastPix PHP SDK
title: '[BUG] '
labels: ['bug', 'needs-triage']
assignees: ''
---

# Bug Report

Thank you for taking the time to report a bug with the FastPix PHP SDK. To help us resolve your issue quickly and efficiently, please provide the following information:

## Description
**Clear and concise description of the bug:**
```
<!-- Please provide a detailed description of what you're experiencing -->
```

## Environment Information

### System Details
- **PHP Version:** [e.g., 8.2, 8.3, 8.4]
- **Operating System:** [e.g., Windows 10, macOS 12.0, Ubuntu 20.04, etc.]
- **Package Manager:** [e.g., Composer]

### SDK Information
- **FastPix PHP SDK Version:** [e.g., 1.0.0, 1.0.1, etc.]
- **Composer Version:** [e.g., 2.5, 2.6, etc.]

## Reproduction Steps

1. **Setup Environment:**
   ```bash
   composer require fastpix/sdk
   ```

2. **Code to Reproduce:**
   ```php
   <?php
   // Please provide a minimal, reproducible example
   require_once 'vendor/autoload.php';

   use FastPix\Sdk\FastpixSDK;
   use FastPix\Sdk\Models\Components\Security;

   $fastpix = new FastpixSDK(
       security: new Security(
           username: 'your-username',
           password: 'your-password'
       )
   );

   // Your code here that causes the issue
   ```

3. **Expected Behavior:**

    ```
    <!-- Describe what you expected to happen -->
    ```

4. **Actual Behavior:**

    ```
    <!-- Describe what actually happened -->
    ```

5. **Error Messages/Logs:**
   ```
   <!-- Paste any error messages, stack traces, or logs here -->
   ```

## Debugging Information

### Console Output
```
<!-- Paste the complete console output here -->
```

### Error Stack Traces
```php
// Complete stack trace for PHP errors
Fatal error: Uncaught Exception: Error message in /path/to/fastpix/file.php:123
Stack trace:
#0 /path/to/your/file.php(45): FastPix\Sdk\SomeClass->someMethod()
#1 {main}
  thrown in /path/to/fastpix/file.php on line 123
```

### HTTP Requests
```http
# Raw HTTP request (remove sensitive headers and credentials)
POST /api/endpoint HTTP/1.1
Host: [FastPix API endpoint]
Authorization: Basic ***
Content-Type: application/json

<!-- Remove credentials and sensitive headers before pasting -->
```

### Screenshots
```
<!-- If applicable, please attach screenshots that help explain your issue -->
```

## Additional Context

### Configuration
```php
<?php
// Please share your SDK configuration (remove sensitive information)
require_once 'vendor/autoload.php';

use FastPix\Sdk\FastpixSDK;
use FastPix\Sdk\Models\Components\Security;

$fastpix = new FastpixSDK(
    security: new Security(
        username: '***',  // Redacted
        password: '***'  // Redacted
    ),
    // Any other configuration options
);
```

### Workarounds
```
<!-- If you've found any workarounds, please describe them here -->
```

## Priority
Please indicate the priority of this bug:

- [ ] Critical (Blocks production use)
- [ ] High (Significant impact on functionality)
- [ ] Medium (Minor impact)
- [ ] Low (Nice to have)

## Checklist
Before submitting, please ensure:

- [ ] I have searched existing issues to avoid duplicates
- [ ] I have provided all required information
- [ ] I have tested with the latest SDK version
- [ ] I have removed any sensitive information (credentials, API keys, etc.)
- [ ] I have provided a minimal reproduction case
- [ ] I have checked the documentation

---

**Thank you for helping improve the FastPix PHP SDK! 🚀**

