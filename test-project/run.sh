#!/bin/bash
# Helper script to run PHP examples easily

set -e

# Check if .env exists
if [ ! -f ".env" ]; then
    echo "⚠️  .env file not found!"
    echo ""
    echo "Please create .env file from .env.example:"
    echo "  cp .env.example .env"
    echo ""
    echo "Then edit .env with your credentials."
    exit 1
fi

# Check if script was provided
if [ -z "$1" ]; then
    echo "Usage: ./run.sh <script_name> [args...]"
    echo ""
    echo "Available scripts:"
    echo "  list_media.php"
    echo "  get_media.php <mediaId>"
    echo "  list_playlists.php"
    echo "  list_signing_keys.php"
    echo "  create_media_example.php"
    echo ""
    echo "Example:"
    echo "  ./run.sh list_media.php"
    echo "  ./run.sh get_media.php your-media-id"
    exit 1
fi

SCRIPT_NAME="$1"
shift # Remove script name from arguments

# Check if script exists
if [ ! -f "$SCRIPT_NAME" ]; then
    echo "❌ Script not found: $SCRIPT_NAME"
    exit 1
fi

# Run the PHP script with remaining arguments
echo "🚀 Running: $SCRIPT_NAME"
echo ""
php "$SCRIPT_NAME" "$@"
