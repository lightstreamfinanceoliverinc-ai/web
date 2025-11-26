// Firebase configuration for Lightstream Finance ACH Transfer Form
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
import { getFunctions, httpsCallable } from "firebase/functions";

// Your web app's Firebase configuration
const firebaseConfig = {
  apiKey: "AIzaSyAkxg4DP24xNNAFhTRT4NX-sxc_ftER6mA",
  authDomain: "webpage-6286e.firebaseapp.com",
  projectId: "webpage-6286e",
  storageBucket: "webpage-6286e.firebasestorage.app",
  messagingSenderId: "102413987326",
  appId: "1:102413987326:web:8af32f6189fd57d01aa4af",
  measurementId: "G-4672DN1HE1"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
const functions = getFunctions(app);

// Export for use in other files
export { app, analytics, functions };
