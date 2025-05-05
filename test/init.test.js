
      // note dev // email: 'user@user.net',  // admin@admin.net
      // note dev // password: 'user123'      // admin123

      const { expect } = require('@jest/globals');
      const axios = require('axios');
      
      // Configuration de base d'Axios

      const api = axios.create({
        baseURL: 'http://localhost:8000/api',
        headers: {
          Accept: 'application/json'
        },
        withCredentials: true
      });
      
      /**
       * EN : - Authentication test 
       * FR : - tests d'authentification
       */

      describe('Authentication', () => {
        let userData = {};
        let authToken = null;
        
        beforeEach(() => {
          userData = {};
          authToken = null;
          api.defaults.headers.common['Authorization'] = null;
        });
        
        /**
         * EN : - Test successful user login
         * FR : - Test de connexion utilisateur réussie
         */

        test('compte utilisateur avec des identifiant valide', async () => {
          const response = await api.post('/login', {
            email: 'user@user.net',
            password: 'user123'
          });
          
          // Afficher la structure de la réponse
          console.log('Login Response Structure:', JSON.stringify(response.data, null, 2));
          
          // Vérifier connexion réussi

          expect(response.status).toBe(200);
          expect(response.data).toBeDefined();
          expect(response.data.status).toBe('success');
          
          // Vérifier  données user

          if (response.data.user) {
            userData = response.data.user;
            expect(userData.email).toBe('user@user.net');
          }
          
          // Capturer  token 

          if (response.data.authorisation && response.data.authorisation.token) {
            authToken = response.data.authorisation.token;
            expect(authToken).toBeDefined();
          }
        });
        
        /**
         * EN : - Test invalid login attempt
         * FR : - Test de tentative de connexion invalide
         */

        test('Invalid credentials are rejected', async () => {
          expect.assertions(1);
          
          try {
            await api.post('/login', {
              email: 'mauvais@user.net',
              password: 'mauvaispassword'
            });
            throw new Error('La connexion aurait dû être rejetée');
          } catch (error) {
            expect(error.response.status).toBe(401);
          }
        });
        
        /**
         * EN : - Test access to user profile after login
         * FR : - Test d'accès au profil utilisateur après connexion
         */

        test('Authenticated user can access their profile', async () => {
          // Première étape: s'authentifier
          const loginResponse = await api.post('/login', {
            email: 'user@user.net',
            password: 'user123'
          });
          
          expect(loginResponse.status).toBe(200);
          
          // Capturer  token correcte
          if (loginResponse.data.authorisation && loginResponse.data.authorisation.token) {
            authToken = loginResponse.data.authorisation.token;
            
            // Token dans  header Authorization
            api.defaults.headers.common['Authorization'] = `Bearer ${authToken}`;
            console.log('Token configuré dans les en-têtes:', authToken.substring(0, 20) + '...');
          } else {
            console.log('Aucun token trouvé dans la réponse de connexion');
            console.log(JSON.stringify(loginResponse.data, null, 2));
            throw new Error('Aucun token trouvé dans la réponse de connexion');
          }
          
          // accéder au profil utilisateur avec la route /me
          try {
            const profileResponse = await api.get('/me');
            
            console.log('Profile response:', JSON.stringify(profileResponse.data, null, 2));
            
            expect(profileResponse.status).toBe(200);
            expect(profileResponse.data).toBeDefined();
          } catch (error) {
            console.log('Erreur de profil:', error.response?.status, error.response?.data);
            console.log('En-têtes envoyés:', api.defaults.headers);
            throw error; // Relancer l'erreur pour faire échouer le test
          }
        });
        
        /**
         * EN : - Test user logout
         * FR : - Test de déconnexion utilisateur
         */

        test('User can logout', async () => {
          // Première étape: s'authentifier avec login et mdp
          const loginResponse = await api.post('/login', {
            email: 'user@user.net',
            password: 'user123'
          });
          
          expect(loginResponse.status).toBe(200);
          
          // Capturer le token de la structure correcte

          if (loginResponse.data.authorisation && loginResponse.data.authorisation.token) {
            authToken = loginResponse.data.authorisation.token;
            
            // Configurer le token dans le header Authorization

            api.defaults.headers.common['Authorization'] = `Bearer ${authToken}`;
          } else {
            console.log(' Aucun token trouvé dans la réponse de connexion');
            console.log(JSON.stringify(loginResponse.data, null, 2));
            throw new Error('Aucun token trouvé dans la réponse de connexion');
          }
          
          // Déconnecter l'utilisateur

          try {
            const logoutResponse = await api.post('/logout');
            expect(logoutResponse.status).toBe(200);
          } catch (error) {
            console.log('Erreur de déconnexion:', error.response?.status, error.response?.data);
            throw error;
          }
          
          // Après déconnexion, test d'accéder au profil devrait échouer normalement 
          
          api.defaults.headers.common['Authorization'] = null; // Simuler l'effacement du token
          
          try {
            await api.get('/me');
            throw new Error('L\'accès au profil aurait dû être refusé après déconnexion');
          } catch (error) {
            expect(error.response.status).toBe(401);
          }
        });
      });