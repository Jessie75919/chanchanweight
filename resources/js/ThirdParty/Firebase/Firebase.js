import firebase from 'firebase/app';
import 'firebase/auth';

export default class FirebaseApp {
    constructor() {
        if (!firebase.apps.length) {
            firebase.initializeApp({
                apiKey: process.env.MIX_APIKEY,
                authDomain: process.env.MIX_AUTH_DOMAIN,
                projectId: process.env.MIX_PROJECT_ID,
                storageBucket: process.env.MIX_STORAGE_BUCKET,
                messagingSenderId: process.env.MIX_MESSAGING_SENDER_ID,
                appId: process.env.MIX_APP_ID,
                measurementId: process.env.MIX_MEASUREMENT_ID,
            });
        }
        this.auth = firebase.auth();
    }

    loginWithGoogle() {
        const provider = new firebase.auth.GoogleAuthProvider();
        return this.auth.signInWithPopup(provider);
    }

    loginWithFB() {
        const provider = new firebase.auth.FacebookAuthProvider();
        return this.auth.signInWithPopup(provider);
    }

    logout() {
        return this.auth.signOut();
    }

    checkAuthStatus() {
        this.auth.onAuthStateChanged(user => {
            if (!user) {
                console.log('sign-out');
            }
        });
    }

}
