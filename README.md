### image-upload-laravel
### Config variables: Using 'b2' driver (backblaze)
- B2_ACCOUNT_ID= Master Application Key keyId
- B2_APP_KEY= Your Application Keys keyId
- B2_APP_KEY_ID= Generated after add a new application key
- B2_BUCKET= Bucket name
### Route
- welcome - Images are public 
- home - Images are private
- profile - force delete images here after deleting in home 
- gallery - 
- /user/{name} - user's public images
### Storage
- backblaze
- user storage limit: 30 MB
- backup : none
### Supported image type: JPG, PNG, GIF or WebP files
### Image size limit: 3 MB
### Images queue limit: 20
### Timeout: 30s
### If images display is wrong, please reload page (F5 or crtl+Shift+R)
