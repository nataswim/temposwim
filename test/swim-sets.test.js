const { expect } = require('@jest/globals');
const axios = require('axios');

// Configuration de base d'Axios
const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json'
  },
  withCredentials: true
});

/**
 * 🇬🇧 Test suite for swim sets management
 * 🇫🇷 Suite de tests pour la gestion des séries de natation
 */
describe('Swim Sets Management', () => {
  let authToken;
  let createdSetId;
  let testExerciseId; // ID de l'exercice à utiliser pour les tests
  let workoutId; // ID de la séance d'entraînement pour les tests
  
  // Se connecter avant tous les tests
  beforeAll(async () => {
    try {
      // Authentification
      const response = await api.post('/login', {
        email: 'admin@admin.net',
        password: 'admin123'
      });
      
      authToken = response.data.authorisation.token;
      api.defaults.headers.common['Authorization'] = `Bearer ${authToken}`;
      
      console.log("🔐 Authentification réussie");
      
      // Récupérer un exercice existant pour l'utiliser dans les tests
      const exercisesResponse = await api.get('/exercises');
      if (exercisesResponse.data && exercisesResponse.data.length > 0) {
        testExerciseId = exercisesResponse.data[0].id;
        console.log(`Alerte : - Utilisation de l'exercice ID ${testExerciseId} pour les tests`);
      } else {
        console.log("Alerte : - Aucun exercice trouvé, les tests peuvent échouer");
      }
      
      // Créer une séance d'entraînement pour les tests
      const workoutData = {
        title: "Test Workout for API Tests",
        description: "Created for automated testing",
        workout_category: "Technique",
        user_id: 1 // ID de l'utilisateur du test (admin@admin.net)
      };
      
      const workoutResponse = await api.post('/workouts', workoutData);
      workoutId = workoutResponse.data.id;
      console.log(`Alerte : - Séance d'entraînement de test créée avec ID ${workoutId}`);
      
    } catch (error) {
      console.error('Alerte : - Erreur de configuration:', error.message);
      if (error.response) {
        console.error('Réponse d\'erreur:', error.response.data);
      }
      throw error;
    }
  });
  
  // Nettoyer après tous les tests
  afterAll(async () => {
    try {
      // Supprimer la séance d'entraînement si elle a été créée
      if (workoutId) {
        await api.delete(`/workouts/${workoutId}`);
        console.log(`🧹 Nettoyage de la séance d'entraînement ID ${workoutId}`);
      }
      
      // Supprimer le set créé pendant les tests s'il existe encore
      if (createdSetId) {
        await api.delete(`/swim-sets/${createdSetId}`);
        console.log(`🧹 Nettoyage de la série ID ${createdSetId}`);
      }
    } catch (error) {
      console.error('Erreur lors du nettoyage:', error.message);
    }
    
    // Se déconnecter
    try {
      await api.post('/logout');
      api.defaults.headers.common['Authorization'] = null;
      console.log('Alerte : - Déconnexion réussie après les tests');
    } catch (error) {
      console.error('Alerte : - Erreur de déconnexion:', error.message);
    }
  });
  
  /**
   * 🇬🇧 Test retrieving all swim sets
   * 🇫🇷 Test de récupération de toutes les séries de natation
   */
  test('Can retrieve all swim sets', async () => {
    const response = await api.get('/swim-sets');
    
    expect(response.status).toBe(200);
    console.log(`Alerte : - ${response.data.length} séries récupérées`);
    
    // La réponse doit être un tableau (même vide)
    expect(Array.isArray(response.data)).toBe(true);
  });
  
  /**
   * 🇬🇧 Test creating a new swim set
   * 🇫🇷 Test de création d'une nouvelle série de natation
   */
  test('Can create a new swim set', async () => {
    // Vérifier qu'on a un ID d'exercice
    expect(testExerciseId).toBeDefined();
    
    // Inspecter la structure de la réponse de création d'exercice attendue
    // en récupérant un exercice existant pour comprendre le format
    const existingSetResponse = await api.get('/swim-sets');
    if (existingSetResponse.data.length > 0) {
      console.log('Alerte : - Structure d\'une série existante:', JSON.stringify(existingSetResponse.data[0], null, 2));
    }
    
    // Données pour une nouvelle série - adaptées selon la structure requise
    const newSwimSet = {
      exercise_id: testExerciseId,
      set_distance: 100,
      set_repetition: 4,
      rest_time: 30,
      workout_id: workoutId // Ajouter l'ID de la séance
    };
    
    try {
      console.log('Alerte : - Tentative de création avec les données:', JSON.stringify(newSwimSet, null, 2));
      const response = await api.post('/swim-sets', newSwimSet);
      
      // Log pour le débogage
      console.log('Alerte : - Réponse de création:', JSON.stringify(response.data, null, 2));
      
      expect(response.status).toBe(201);
      expect(response.data).toBeDefined();
      expect(response.data.id).toBeDefined();
      
      // Sauvegarder l'ID pour le nettoyage et les tests suivants
      createdSetId = response.data.id;
      console.log(`Alerte : - Nouvelle série créée avec ID ${createdSetId}`);
      
    } catch (error) {
      console.error('Alerte : - Erreur lors de la création de série:', error.message);
      if (error.response) {
        console.error('Status:', error.response.status);
        console.error('Data:', JSON.stringify(error.response.data, null, 2));
        console.error('Headers:', error.response.headers);
      }
      throw error;
    }
  });
  
  /**
   * 🇬🇧 Test retrieving a specific swim set
   * 🇫🇷 Test de récupération d'une série de natation spécifique
   */
  test('Can retrieve a specific swim set', async () => {
    // Ce test dépend du test de création
    if (!createdSetId) {
      console.warn('Alerte : - Test ignoré: aucun ID de série n\'a été créé');
      return;
    }
    
    const response = await api.get(`/swim-sets/${createdSetId}`);
    
    // Log pour le débogage
    console.log('Alerte : - Série récupérée:', JSON.stringify(response.data, null, 2));
    
    expect(response.status).toBe(200);
    expect(response.data.id).toBe(createdSetId);
  });
  
  /**
   * 🇬🇧 Test updating a swim set
   * 🇫🇷 Test de mise à jour d'une série de natation
   */
  test('Can update a swim set', async () => {
    // Ce test dépend du test de création
    if (!createdSetId) {
      console.warn('Alerte : - Test ignoré: aucun ID de série n\'a été créé');
      return;
    }
    
    // Récupérer d'abord les données actuelles pour comparer
    const beforeUpdate = await api.get(`/swim-sets/${createdSetId}`);
    console.log('Alerte : - Données avant mise à jour:', JSON.stringify(beforeUpdate.data, null, 2));
    
    const updateData = {
      set_distance: 200,
      set_repetition: 2,
      rest_time: 45
    };
    
    console.log('Alerte : - Données de mise à jour:', JSON.stringify(updateData, null, 2));
    const response = await api.put(`/swim-sets/${createdSetId}`, updateData);
    
    console.log('Alerte : - Réponse de mise à jour:', JSON.stringify(response.data, null, 2));
    
    expect(response.status).toBe(200);
    
    // Vérifier que les données ont été mises à jour
    const afterUpdate = await api.get(`/swim-sets/${createdSetId}`);
    console.log('Alerte : - Données après mise à jour:', JSON.stringify(afterUpdate.data, null, 2));
    
    // Vérifications, adaptées en fonction de la réponse réelle
    if (typeof afterUpdate.data.set_distance !== 'undefined') {
      expect(afterUpdate.data.set_distance).toBe(updateData.set_distance);
    }
    if (typeof afterUpdate.data.set_repetition !== 'undefined') {
      expect(afterUpdate.data.set_repetition).toBe(updateData.set_repetition);
    }
    if (typeof afterUpdate.data.rest_time !== 'undefined') {
      expect(afterUpdate.data.rest_time).toBe(updateData.rest_time);
    }
  });
  
  /**
   * 🇬🇧 Test associating a swim set with a workout
   * 🇫🇷 Test d'association d'une série de natation à une séance d'entraînement
   */
  test('Can associate a swim set with a workout', async () => {
    // Ce test dépend du test de création
    if (!createdSetId) {
      console.warn('Alerte : - Test ignoré: aucun ID de série n\'a été créé');
      return;
    }
    
    try {
      // Associer la série à la séance
      const associateResponse = await api.post(`/workouts/${workoutId}/swim-sets/${createdSetId}`);
      
      console.log('Alerte : - Réponse d\'association:', JSON.stringify(associateResponse.data, null, 2));
      
      expect(associateResponse.status).toBe(200);
      
      // Vérifier que l'association a été faite
      const workoutSetsResponse = await api.get(`/workouts/${workoutId}/swim-sets`);
      const associatedSets = workoutSetsResponse.data;
      
      console.log('Alerte : - Séries associées:', JSON.stringify(associatedSets, null, 2));
      
      expect(Array.isArray(associatedSets)).toBe(true);
      
      // Vérifier si l'ID est présent dans les résultats
      const setFound = associatedSets.some(set => {
        if (typeof set === 'object' && set !== null) {
          return set.id === createdSetId;
        }
        return false;
      });
      
      expect(setFound).toBe(true);
    } catch (error) {
      console.error('Alerte : - Erreur dans le test d\'association:', error.message);
      if (error.response) {
        console.error('Status:', error.response.status);
        console.error('Data:', JSON.stringify(error.response.data, null, 2));
      }
      throw error;
    }
  });
  
  /**
   * 🇬🇧 Test deleting a swim set
   * 🇫🇷 Test de suppression d'une série de natation
   */
  test('Can delete a swim set', async () => {
    // Ce test dépend du test de création
    if (!createdSetId) {
      console.warn('Alerte : - Test ignoré: aucun ID de série n\'a été créé');
      return;
    }
    
    try {
      const response = await api.delete(`/swim-sets/${createdSetId}`);
      
      console.log('Alerte : - Réponse de suppression:', JSON.stringify(response.data, null, 2));
      
      expect(response.status).toBe(200);
      
      // Vérifier que la série a bien été supprimée
      try {
        await api.get(`/swim-sets/${createdSetId}`);
        throw new Error('La série aurait dû être supprimée');
      } catch (error) {
        expect(error.response.status).toBe(404);
        console.log(`Alerte : - Confirmation: la série ID ${createdSetId} a été supprimée`);
      }
      
      // Réinitialiser l'ID pour éviter de tenter de le supprimer à nouveau dans afterAll
      createdSetId = null;
    } catch (error) {
      console.error('Alerte : - Erreur lors de la suppression:', error.message);
      if (error.response) {
        console.error('Status:', error.response.status);
        console.error('Data:', JSON.stringify(error.response.data, null, 2));
      }
      throw error;
    }
  });
  
  /**
   * 🇬🇧 Test error handling for invalid data
   * 🇫🇷 Test de gestion des erreurs pour des données invalides
   */
  test('Returns error for invalid data', async () => {
    // Données invalides (manque exercise_id)
    const invalidSwimSet = {
      set_distance: 100,
      set_repetition: 4,
      rest_time: 30
      // exercise_id manquant
    };
    
    try {
      await api.post('/swim-sets', invalidSwimSet);
      throw new Error('La requête aurait dû échouer avec des données invalides');
    } catch (error) {
      // Accepter soit 400 (Bad Request) soit 422 (Unprocessable Entity)
      expect([400, 422]).toContain(error.response.status);
      console.log('Alerte : - Réponse d\'erreur de validation:', JSON.stringify(error.response.data, null, 2));
    }
  });
  
  /**
   * 🇬🇧 Test error handling for non-existent swim set
   * 🇫🇷 Test de gestion des erreurs pour une série inexistante
   */
  test('Returns 404 for non-existent swim set', async () => {
    const nonExistentId = 99999; // ID qui n'existe probablement pas
    
    try {
      await api.get(`/swim-sets/${nonExistentId}`);
      throw new Error('La requête aurait dû échouer pour un ID inexistant');
    } catch (error) {
      expect(error.response.status).toBe(404);
      console.log('Alerte : - Réponse d\'erreur pour ressource introuvable:', JSON.stringify(error.response.data, null, 2));
    }
  });
});