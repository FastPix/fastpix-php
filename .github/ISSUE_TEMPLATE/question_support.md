---
name: Question/Support
about: Ask questions or get help with the FastPix PHP SDK
title: '[QUESTION] '
labels: ['question', 'needs-triage']
assignees: ''
---

# Question/Support

Thank you for reaching out! We're here to help you with the FastPix PHP SDK. Please provide the following information:

## Question Type
- [ ] How to use a specific feature
- [ ] Integration help
- [ ] Configuration question
- [ ] Performance question
- [ ] Troubleshooting help
- [ ] Error handling
- [ ] Composer installation
- [ ] Other: _______________

## Question
**What would you like to know?**
```
<!-- Please provide a clear, specific question -->
```
## What You've Tried
**What have you already attempted to solve this?**

```php
<?php
// Please share any code you've tried
require_once 'vendor/autoload.php';

use FastPix\Sdk\FastpixSDK;
use FastPix\Sdk\Models\Components\Security;

$fastpix = new FastpixSDK(
    security: new Security(
        username: 'your-username',
        password: 'your-password'
    )
);

// Your attempted code here
```

## Current Setup
**Describe your current setup:**

### Environment
- **PHP Version:** [e.g., 8.2, 8.3, 8.4]
- **Operating System:** [e.g., Windows 10, macOS 12.0, Ubuntu 20.04, etc.]
- **FastPix PHP SDK Version:** [e.g., 1.0.0, 1.0.1]
- **Composer Version:** [e.g., 2.5, 2.6, etc.]
- **Package Manager:** [e.g., Composer]

### Configuration
```php
<?php
// Your current SDK configuration (remove sensitive information)
require_once 'vendor/autoload.php';

use FastPix\Sdk\FastpixSDK;
use FastPix\Sdk\Models\Components\Security;

$fastpix = new FastpixSDK(
    security: new Security(
        username: '***',  // Redacted
        password: '***'  // Redacted
    ),
    // Any other configuration
);
```

## Expected Outcome
**What are you trying to achieve?**
```
<!-- Describe your end goal -->
```
## Error Messages (if any)
```
<!-- If you're getting errors, paste them here -->
```

## Additional Context

### Use Case
**What are you building?**

- [ ] Web application (Laravel, Symfony, etc.)
- [ ] REST API service
- [ ] CLI application
- [ ] Background job processor
- [ ] Library/package
- [ ] Other: _______________

### Project Details
- **Project Type:** [e.g., Laravel app, Symfony app, CLI tool, etc.]
- **Framework:** [e.g., Laravel, Symfony, etc.]

### Timeline
**When do you need this resolved?**

- [ ] ASAP (blocking development)
- [ ] This week
- [ ] This month
- [ ] No rush

### Resources Checked
**What resources have you already checked?**

- [ ] README.md
- [ ] Documentation
- [ ] Examples
- [ ] Stack Overflow
- [ ] GitHub Issues
- [ ] PHP documentation
- [ ] Other: _______________

## Priority
Please indicate the urgency:

- [ ] Critical (Blocking production deployment)
- [ ] High (Blocking development)
- [ ] Medium (Would like to know soon)
- [ ] Low (Just curious)

## Checklist
Before submitting, please ensure:

- [ ] I have provided a clear question
- [ ] I have described what I've tried
- [ ] I have included my current setup
- [ ] I have checked existing documentation
- [ ] I have provided sufficient context
- [ ] I have removed any sensitive information (credentials, API keys, etc.)

---

**We'll do our best to help you get unstuck! 🚀**

**For urgent issues, please also consider:**
- [FastPix Documentation](https://docs.fastpix.io/)
- [Stack Overflow](https://stackoverflow.com/questions/tagged/fastpix)
- [GitHub Discussions](https://github.com/FastPix/fastpix-php/discussions)

