#!/bin/bash
# SQL Migration Helper Script
# Simplifies execution of SQL migrations across multiple TAG databases

CONTAINER_NAME="tag-app"
YIIC_PATH="/app/app/yiic"
MIGRATIONS_DIR="/app/app/migrations"

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Function to display usage
usage() {
    echo -e "${GREEN}SQL Migration Helper${NC}"
    echo ""
    echo "Usage:"
    echo "  ./migrate.sh <sql-file> [--dry-run]"
    echo ""
    echo "Examples:"
    echo "  ./migrate.sh app/migrations/inventory_complete.sql"
    echo "  ./migrate.sh app/migrations/inventory_complete.sql --dry-run"
    echo ""
    echo "Options:"
    echo "  --dry-run    Preview which databases would be affected without executing"
    exit 1
}

# Check if file argument is provided
if [ -z "$1" ]; then
    echo -e "${RED}Error: SQL file path is required${NC}"
    usage
fi

SQL_FILE="$1"
DRY_RUN=""

# Check for dry-run flag
if [ "$2" == "--dry-run" ]; then
    DRY_RUN="--dry-run"
    echo -e "${YELLOW}Running in DRY-RUN mode (no changes will be made)${NC}"
fi

# Convert local path to container path if needed
if [[ "$SQL_FILE" == app/* ]]; then
    CONTAINER_SQL_FILE="/app/$SQL_FILE"
elif [[ "$SQL_FILE" != /* ]]; then
    CONTAINER_SQL_FILE="$MIGRATIONS_DIR/$SQL_FILE"
else
    CONTAINER_SQL_FILE="$SQL_FILE"
fi

echo -e "${GREEN}Executing SQL migration...${NC}"
echo "Container: $CONTAINER_NAME"
echo "SQL File: $CONTAINER_SQL_FILE"
echo ""

# Execute the migration
docker exec "$CONTAINER_NAME" php "$YIIC_PATH" sqlmigration run "$CONTAINER_SQL_FILE" $DRY_RUN

# Check exit code
if [ $? -eq 0 ]; then
    echo ""
    echo -e "${GREEN}✓ Migration completed successfully${NC}"
else
    echo ""
    echo -e "${RED}✗ Migration failed${NC}"
    exit 1
fi
