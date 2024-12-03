#!/bin/bash

# Project ID
PROJECT_ID="artoz-backend"

# Array of environment variables to store in Secret Manager
declare -A SECRETS=(
  [APP_NAME]="ArtoZ-server"
  [APP_ENV]="production"
  [APP_KEY]="base64:nS9zEEg9j3ryKohAvtmL6huSJ8mx+x8Wn6OVBD4Yt1g="
  [APP_DEBUG]="false"
  [APP_TIMEZONE]="Asia/Jakarta"
  [APP_URL]="http://localhost"
  [DB_CONNECTION]="mysql"
  [DB_HOST]="34.34.219.16"
  [DB_PORT]="3306"
  [DB_DATABASE]="artoz_db"
  [DB_USERNAME]="artoz"
  [DB_PASSWORD]="artoz"
)

# Loop through each variable and create/update secrets in Secret Manager
for SECRET in "${!SECRETS[@]}"; do
  VALUE="${SECRETS[$SECRET]}"
  echo "Creating/Updating secret: $SECRET"
  echo -n "$VALUE" | gcloud secrets create "$SECRET" \
    --data-file=- \
    --replication-policy="automatic" \
    --project "$PROJECT_ID" 2>/dev/null || \
    gcloud secrets versions add "$SECRET" --data-file=- --project "$PROJECT_ID"
done
