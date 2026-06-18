#!/bin/bash

# FastPix PHP SDK Release Script
# This script helps create and publish a new version

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    local message="$1"
    echo -e "${BLUE}[INFO]${NC} $message"
}

print_success() {
    local message="$1"
    echo -e "${GREEN}[SUCCESS]${NC} $message"
}

print_warning() {
    local message="$1"
    echo -e "${YELLOW}[WARNING]${NC} $message"
}

print_error() {
    local message="$1"
    echo -e "${RED}[ERROR]${NC} $message" >&2
}

# Check if we're in a git repository
if ! git rev-parse --git-dir > /dev/null 2>&1; then
    print_error "Not in a git repository!"
    exit 1
fi

# Check if there are uncommitted changes
if ! git diff-index --quiet HEAD --; then
    print_error "You have uncommitted changes. Please commit or stash them first."
    exit 1
fi

# Get version from user
read -p "Enter version number (e.g., 1.0.0): " VERSION

if [[ ! $VERSION =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
    print_error "Invalid version format. Use semantic versioning (e.g., 1.0.0)"
    exit 1
fi

print_status "Releasing version $VERSION..."

# Update composer.json version
print_status "Updating composer.json version..."
sed -i.bak "s/\"version\": \".*\"/\"version\": \"$VERSION\"/" composer.json
rm composer.json.bak

# Run tests
print_status "Running tests..."
composer test

# Run PHPStan
print_status "Running PHPStan analysis..."
composer stan

# Validate composer.json
print_status "Validating composer.json..."
composer validate --strict

# Commit changes
print_status "Committing changes..."
git add composer.json CHANGELOG.md
git commit -m "Release version $VERSION

- Updated composer.json version to $VERSION
- Updated CHANGELOG.md with release notes
- All tests passing
- PHPStan analysis clean"

# Create and push tag
print_status "Creating and pushing tag v$VERSION..."
git tag -a "v$VERSION" -m "Release version $VERSION"
git push origin main
git push origin "v$VERSION"

print_success "Version $VERSION released successfully!"
print_status "The GitHub Actions workflow will now:"
print_status "1. Run the full test suite"
print_status "2. Perform security scans"
print_status "3. Notify Packagist to update the package"
print_status "4. Create a GitHub release"

print_warning "Make sure you have the following secrets configured in your GitHub repository:"
print_warning "- PACKAGIST_USERNAME: Your Packagist username"
print_warning "- PACKAGIST_TOKEN: Your Packagist API token"

print_status "You can monitor the release process at:"
print_status "https://github.com/FastPix/fastpix-php/actions"
