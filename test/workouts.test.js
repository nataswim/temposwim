// TETS  fonctionnalités principales de gestion des séances 
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
 * EN: - Test suite for workouts management
 * FR: - Suite de tests pour la gestion des séances d'entraînement
 */
describe('Workouts Management', () => {
  let authToken;
  let createdWorkoutId;
  let testExerciseId;
  let testSwimSetId;
  let userId;
  
  // Se connecter avant tous les tests
  beforeAll(async () => {
    try {
      // Authentification
      const response = await api.post('/login', {
        email: 'admin@admin.net',
        password: 'admin123'
      });
      
      authToken = response.data.authorisation.token;
      userId = response.data.user.id;
      api.defaults.headers.common['Authorization'] = `Bearer ${authToken}`;
      
      console.log(`ALERTE : - Authentification réussie avec l'utilisateur ID ${userId}`);
      
      // Récupérer ou créer un exercice pour les tests
      const exercisesResponse = await api.get('/exercises');
      if (exercisesResponse.data && exercisesResponse.data.length > 0) {
        testExerciseId = exercisesResponse.data[0].id;
        console.log(`ALERTE : - Utilisation de l'exercice ID ${testExerciseId} pour les tests`);
      } else {
        // Créer un exercice 
        const newExercise = {
          title: "Test Exercise for Workouts",
          description: "Created for automated testing",
          exercise_level: "Débutant",
          exercise_category: "Technique"
        };
        const createExerciseResponse = await api.post('/exercises', newExercise);
        testExerciseId = createExerciseResponse.data.id;
        console.log(`ALERTE : - Exercice créé avec ID ${testExerciseId}`);
      }
      
      // Récupérer ou créer une série de natation pour les tests
      const swimSetsResponse = await api.get('/swim-sets');
      if (swimSetsResponse.data && swimSetsResponse.data.length > 0) {
        testSwimSetId = swimSetsResponse.data[0].id;
        console.log(`INFOS : - Utilisation de la série ID ${testSwimSetId} pour les tests`);
      } else {
        // Créer une série 
        const newSwimSet = {
          exercise_id: testExerciseId,
          set_distance: 100,
          set_repetition: 4,
          rest_time: 30
        };
        const createSwimSetResponse = await api.post('/swim-sets', newSwimSet);
        testSwimSetId = createSwimSetResponse.data.id;
        console.log(`INFOS : - Série créée avec ID ${testSwimSetId}`);
      }
    } catch (error) {
      console.error('ALERTE : - Erreur de configuration:', error.message);
      if (error.response) {
        console.error('Réponse d\'erreur:', error.response.data);
      }
      throw error;
    }
  });
  
  // Nettoyer après  les tests
  afterAll(async () => {
    // Supprimer la séance créée pendant les tests s'il existe encore
    if (createdWorkoutId) {
      try {
        // Vérifier d'abord si la séance existe encore
        try {
          await api.get(`/workouts/${createdWorkoutId}`);
          // Si on arrive ici, la séance existe encore, on peut la supprimer
          await api.delete(`/workouts/${createdWorkoutId}`);
          console.log(`ALERTE : -  Nettoyage de la séance ID ${createdWorkoutId}`);
        } catch (checkError) {
          // La séance n'existe plus, rien à faire
          console.log(`ALERTE : - La séance ID ${createdWorkoutId} n'existe plus, pas besoin de nettoyage`);
        }
      } catch (error) {
        console.error('Erreur lors du nettoyage de la séance:', error.message);
      }
    }
    
    // Se déconnecter
    try {
      await api.post('/logout');
      api.defaults.headers.common['Authorization'] = null;
      console.log('ALERTE : - Déconnexion réussie après les tests');
    } catch (error) {
      console.error('Erreur de déconnexion:', error.message);
    }
  });
  
  /**
   * EN: - Test retrieving all workouts
   * FR: - Test de récupération de toutes les séances d'entraînement
   */
  test('Can retrieve all workouts', async () => {
    const response = await api.get('/workouts');
    
    expect(response.status).toBe(200);
    console.log(`ALERTE : - ${response.data.length} séances récupérées`);
    
    // La réponse doit être un tableau
    expect(Array.isArray(response.data)).toBe(true);
    
    // Afficher la structure d'une séance si disponible
    if (response.data.length > 0) {
      console.log('ALERTE : - Structure d\'une séance existante:', JSON.stringify(response.data[0], null, 2));
    }
  });
  
  
  /**
   * EN: - Test creating a new workout
   * FR: - Test de création d'une nouvelle séance d'entraînement
   */
  test('Can create a new workout', async () => {
    // Données pour une nouvelle séance
    const newWorkout = {
      title: `Test Workout ${Date.now()}`,
      description: "Séance de test créée via l'API pour les tests automatisés",
      workout_category: "Technique",
      user_id: userId
    };
    
    try {
      console.log('ALERTE : - Tentative de création avec les données:', JSON.stringify(newWorkout, null, 2));
      const response = await api.post('/workouts', newWorkout);
      
      // Log pour le débogage
      console.log('ALERTE : - Réponse de création:', JSON.stringify(response.data, null, 2));
      
      expect(response.status).toBe(201);
      expect(response.data.id).toBeDefined();
      
      // Sauvegarder l'ID pour le nettoyage et les tests suivants
      createdWorkoutId = response.data.id;
      console.log(`ALERTE : - Nouvelle séance créée avec ID ${createdWorkoutId}`);
      
      // Vérifier que les données ont été correctement enregistrées
      expect(response.data.title).toBe(newWorkout.title);
      expect(response.data.workout_category).toBe(newWorkout.workout_category);
    } catch (error) {
      console.error('ALERTE : - Erreur lors de la création de séance:', error.message);
      if (error.response) {
        console.error('Status:', error.response.status);
        console.error('Data:', JSON.stringify(error.response.data, null, 2));
      }
      throw error;
    }
  });
  
  /**
   * EN: - Test retrieving a specific workout
   * FR: - Test de récupération d'une séance spécifique
   */
  test('Can retrieve a specific workout', async () => {
    // Ce test dépend du test de création
    if (!createdWorkoutId) {
      console.warn('ALERTE : - Test ignoré: aucun ID de séance n\'a été créé');
      return;
    }
    
    const response = await api.get(`/workouts/${createdWorkoutId}`);
    
    console.log('ALERTE : - Séance récupérée:', JSON.stringify(response.data, null, 2));
    
    expect(response.status).toBe(200);
    expect(response.data.id).toBe(createdWorkoutId);
  });
  
  /**
   * EN: - Test updating a workout
   * FR: - Test de mise à jour d'une séance
   */
  test('Can update a workout', async () => {
    // Ce test dépend du test de création
    if (!createdWorkoutId) {
      console.warn('ALERTE : - Test ignoré: aucun ID de séance n\'a été créé');
      return;
    }
    
    // Récupérer d'abord les données actuelles
    const currentData = await api.get(`/workouts/${createdWorkoutId}`);
    
    // Mise à jour avec les données complètes requises par l'API
    const updateData = {
      title: `Updated Workout ${Date.now()}`,
      description: "Séance mise à jour via les tests automatisés",
      workout_category: "Récupération",
      // Conserver l'ID utilisateur existant
      user_id: currentData.data.user_id
    };
    
    console.log('ALERTE : - Données de mise à jour:', JSON.stringify(updateData, null, 2));
    const response = await api.put(`/workouts/${createdWorkoutId}`, updateData);
    
    console.log('ALERTE : - Réponse de mise à jour:', JSON.stringify(response.data, null, 2));
    
    expect(response.status).toBe(200);
    
    // Vérifier que les données ont été mises à jour
    const updatedWorkout = await api.get(`/workouts/${createdWorkoutId}`);
    console.log('ALERTE : - Séance après mise à jour:', JSON.stringify(updatedWorkout.data, null, 2));
    
    expect(updatedWorkout.data.title).toBe(updateData.title);
    expect(updatedWorkout.data.workout_category).toBe(updateData.workout_category);
  });
  
  /**
   * EN: - Test adding an exercise to a workout
   * FR: - Test d'ajout d'un exercice à une séance
   */
  test('Can add an exercise to a workout', async () => {
    // Ce test dépend du test de création
    if (!createdWorkoutId || !testExerciseId) {
      console.warn('ALERTE : - Test ignoré: aucun ID de séance ou d\'exercice n\'est disponible');
      return;
    }
    
    try {
      console.log(`ALERTE : - Ajout de l'exercice ${testExerciseId} à la séance ${createdWorkoutId}`);
      const response = await api.post(`/workouts/${createdWorkoutId}/exercises/${testExerciseId}`);
      
      console.log('ALERTE : - Réponse d\'ajout d\'exercice:', JSON.stringify(response.data, null, 2));
      
      // Adapter l'assertion au code de statut réel
      expect([200, 201]).toContain(response.status);
      
      // Vérifier que l'exercice a bien été ajouté
      const exercisesResponse = await api.get(`/workouts/${createdWorkoutId}/exercises`);
      const exercises = exercisesResponse.data;
      
      console.log('ALERTE : - Exercices de la séance:', JSON.stringify(exercises, null, 2));
      
      expect(Array.isArray(exercises)).toBe(true);
      
      // Vérifier que l'exercice est bien dans la liste
      const exerciseFound = exercises.some(ex => {
        if (typeof ex === 'object' && ex !== null) {
          return ex.id === testExerciseId;
        }
        return false;
      });
      
      expect(exerciseFound).toBe(true);
    } catch (error) {
      console.error('ALERTE : - Erreur lors de l\'ajout d\'exercice:', error.message);
      if (error.response) {
        console.error('Status:', error.response.status);
        console.error('Data:', JSON.stringify(error.response.data, null, 2));
      }
      throw error;
    }
  });
  
  /**
   * EN: - Test adding a swim set to a workout
   * FR: - Test d'ajout d'une série de natation à une séance
   */
  test('Can add a swim set to a workout', async () => {
    // Ce test dépend du test de création
    if (!createdWorkoutId || !testSwimSetId) {
      console.warn('ALERTE : - Test ignoré: aucun ID de séance ou de série n\'est disponible');
      return;
    }
    
    try {
      console.log(`INFOS : - Ajout de la série ${testSwimSetId} à la séance ${createdWorkoutId}`);
      const response = await api.post(`/workouts/${createdWorkoutId}/swim-sets/${testSwimSetId}`);
      
      console.log('ALERTE : - Réponse d\'ajout de série:', JSON.stringify(response.data, null, 2));
      
      // Adapter l'assertion au code de statut réel
      expect([200, 201]).toContain(response.status);
      
      // Vérifier que la série a bien été ajoutée
      const setsResponse = await api.get(`/workouts/${createdWorkoutId}/swim-sets`);
      const sets = setsResponse.data;
      
      console.log('INFOS : - Séries de la séance:', JSON.stringify(sets, null, 2));
      
      expect(Array.isArray(sets)).toBe(true);
      
      // Vérifier que la série est bien dans la liste
      const setFound = sets.some(set => {
        if (typeof set === 'object' && set !== null) {
          return set.id === testSwimSetId;
        }
        return false;
      });
      
      expect(setFound).toBe(true);
    } catch (error) {
      console.error('ALERTE : - Erreur lors de l\'ajout de série:', error.message);
      if (error.response) {
        console.error('Status:', error.response.status);
        console.error('Data:', JSON.stringify(error.response.data, null, 2));
      }
      throw error;
    }
  });
  
  /**
   * EN: - Test removing an exercise from a workout
   * FR: - Test de suppression d'un exercice d'une séance
   */
  test('Can remove an exercise from a workout', async () => {
    // Ce test dépend de l'ajout d'exercice
    if (!createdWorkoutId || !testExerciseId) {
      console.warn('ALERTE : - Test ignoré: aucun ID de séance ou d\'exercice n\'est disponible');
      return;
    }
    
    try {
      console.log(`ALERTE : - Suppression de l'exercice ${testExerciseId} de la séance ${createdWorkoutId}`);
      const response = await api.delete(`/workouts/${createdWorkoutId}/exercises/${testExerciseId}`);
      
      console.log('ALERTE : - Réponse de suppression d\'exercice:', JSON.stringify(response.data, null, 2));
      
      // Adapter l'assertion aux codes de statut HTTP standards pour les suppressions
      expect([200, 204]).toContain(response.status);
      
      // Vérifier que l'exercice a bien été supprimé
      const exercisesResponse = await api.get(`/workouts/${createdWorkoutId}/exercises`);
      const exercises = exercisesResponse.data;
      
      // Vérifier que l'exercice n'est plus dans la liste
      const exerciseFound = exercises.some(ex => {
        if (typeof ex === 'object' && ex !== null) {
          return ex.id === testExerciseId;
        }
        return false;
      });
      
      expect(exerciseFound).toBe(false);
    } catch (error) {
      console.error('ALERTE : - Erreur lors de la suppression d\'exercice:', error.message);
      if (error.response) {
        console.error('Status:', error.response.status);
        console.error('Data:', JSON.stringify(error.response.data, null, 2));
      }
      throw error;
    }
  });
  
  /**
   * EN: - Test removing a swim set from a workout
   * FR: - Test de suppression d'une série de natation d'une séance
   */
  test('Can remove a swim set from a workout', async () => {
    // Ce test dépend de l'ajout de série
    if (!createdWorkoutId || !testSwimSetId) {
      console.warn('ALERTE : - Test ignoré: aucun ID de séance ou de série n\'est disponible');
      return;
    }
    
    try {
      console.log(`ALERTE : - Suppression de la série ${testSwimSetId} de la séance ${createdWorkoutId}`);
      const response = await api.delete(`/workouts/${createdWorkoutId}/swim-sets/${testSwimSetId}`);
      
      console.log('ALERTE : - Réponse de suppression de série:', JSON.stringify(response.data, null, 2));
      
      // Adapter l'assertion aux codes de statut HTTP standards pour les suppressions
      expect([200, 204]).toContain(response.status);
      
      // Vérifier que la série a bien été supprimée
      const setsResponse = await api.get(`/workouts/${createdWorkoutId}/swim-sets`);
      const sets = setsResponse.data;
      
      // Vérifier que la série n'est plus dans la liste
      const setFound = sets.some(set => {
        if (typeof set === 'object' && set !== null) {
          return set.id === testSwimSetId;
        }
        return false;
      });
      
      expect(setFound).toBe(false);
    } catch (error) {
      console.error('ALERTE : - Erreur lors de la suppression de série:', error.message);
      if (error.response) {
        console.error('Status:', error.response.status);
        console.error('Data:', JSON.stringify(error.response.data, null, 2));
      }
      throw error;
    }
  });
  
  /**
   * EN: - Test getting plans for a workout
   * FR: - Test de récupération des plans pour une séance
   */
  test('Can retrieve plans for a workout', async () => {
    // Ce test dépend du test de création
    if (!createdWorkoutId) {
      console.warn('ALERTE : - Test ignoré: aucun ID de séance n\'a été créé');
      return;
    }
    
    try {
      const response = await api.get(`/workouts/${createdWorkoutId}/plans`);
      
      console.log('ALERTE : - Plans pour la séance:', JSON.stringify(response.data, null, 2));
      
      expect(response.status).toBe(200);
      expect(Array.isArray(response.data)).toBe(true);
    } catch (error) {
      console.error('ALERTE : - Erreur lors de la récupération des plans:', error.message);
      if (error.response) {
        console.error('Status:', error.response.status);
        console.error('Data:', JSON.stringify(error.response.data, null, 2));
      }
      throw error;
    }
  });
  
  /**
   * EN: - Test deleting a workout
   * FR: - Test de suppression d'une séance
   */
  test('Can delete a workout', async () => {
    // Ce test dépend du test de création
    if (!createdWorkoutId) {
      console.warn('ALERTE : - Test ignoré: aucun ID de séance n\'a été créé');
      return;
    }
    
    try {
      const response = await api.delete(`/workouts/${createdWorkoutId}`);
      
      console.log('ALERTE : - Réponse de suppression:', JSON.stringify(response.data, null, 2));
      
      // Adapter l'assertion aux codes de statut HTTP standards pour les suppressions
      expect([200, 204]).toContain(response.status);
      
      // Vérifier que la séance a bien été supprimée
      try {
        await api.get(`/workouts/${createdWorkoutId}`);
        throw new Error('La séance aurait dû être supprimée');
      } catch (error) {
        expect(error.response.status).toBe(404);
        console.log(`ALERTE : - Confirmation: la séance ID ${createdWorkoutId} a été supprimée`);
      }
      
      // Réinitialiser l'ID pour éviter de tenter de le supprimer à nouveau dans afterAll
      createdWorkoutId = null;
    } catch (error) {
      console.error('ALERTE : - Erreur lors de la suppression:', error.message);
      if (error.response) {
        console.error('Status:', error.response.status);
        console.error('Data:', JSON.stringify(error.response.data, null, 2));
      }
      throw error;
    }
  });
  
  /**
   * EN: - Test error handling for non-existent workout
   * FR: - Test de gestion des erreurs pour une séance inexistante
   */
  test('Returns 404 for non-existent workout', async () => {
    const nonExistentId = 99999; // ID qui n'existe probablement pas
    
    try {
      await api.get(`/workouts/${nonExistentId}`);
      throw new Error('La requête aurait dû échouer pour un ID inexistant');
    } catch (error) {
      expect(error.response.status).toBe(404);
      console.log('ALERTE : - Réponse d\'erreur pour ressource introuvable:', JSON.stringify(error.response.data, null, 2));
    }
  });
});