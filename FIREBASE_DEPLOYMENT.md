# Firebase Deployment Guide - Lightstream Finance ACH Transfer Form

## 🚀 Deploy to Firebase Hosting

### Prerequisites
- Firebase CLI installed
- Firebase project: `webpage-6286e`
- Node.js installed

### Quick Deployment Steps

#### 1. Install Firebase CLI (if not installed)
```bash
npm install -g firebase-tools
```

#### 2. Login to Firebase
```bash
firebase login
```

#### 3. Initialize Firebase (Already configured)
The project is already configured with:
- `firebase.json` - Hosting configuration
- `.firebaserc` - Project settings
- Firebase config integrated in `index.html`

#### 4. Deploy to Firebase Hosting
```bash
firebase deploy --only hosting
```

### 🌐 Your Live URL
After deployment, your site will be available at:
**https://webpage-6286e.firebaseapp.com**

### ⚠️ Important Notes

#### PHP Backend Limitation
Firebase Hosting only supports static files. The PHP backend (`AjaxForm.php`) won't work directly. You have two options:

#### Option 1: Use Firebase Functions (Recommended)
1. Convert `AjaxForm.php` to a Firebase Function
2. Update `AjaxForm.js` to call the Firebase Function
3. Deploy functions with: `firebase deploy --only functions`

#### Option 2: Use External API
1. Host the PHP backend on a separate server (Heroku, DigitalOcean, etc.)
2. Update `AjaxForm.js` to point to the external API
3. Configure CORS headers

### 📁 Files Ready for Deployment
- ✅ `index.html` - Main form (Firebase SDK integrated)
- ✅ `AjaxForm.js` - Frontend logic
- ✅ `firebase.json` - Hosting configuration
- ✅ `.firebaserc` - Project configuration
- ✅ `firebase-config.js` - Firebase configuration module

### 🔧 Configuration
Firebase is configured for project: `webpage-6286e`
- Domain: `webpage-6286e.firebaseapp.com`
- Analytics: Enabled
- Hosting: Configured

### 🚀 Deployment Commands
```bash
# Deploy everything
firebase deploy

# Deploy only hosting
firebase deploy --only hosting

# Deploy only functions (if you create them)
firebase deploy --only functions
```

### 📊 Post-Deployment
After deployment:
1. Test the form interface at your Firebase URL
2. Configure the backend solution (Functions or external API)
3. Update DNS if using custom domain
4. Test email functionality

---
**© 2025 Lightstream Finance - Firebase Deployment Ready**
