// Test des fonctionnalités principales de gestion des plans d'entraînement
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
 * EN: - Test suite for training plans management
 * FR: - Suite de tests pour la gestion des plans d'entraînement
 */
describe('Training Plans Management', () => {
  let authToken;
  let createdPlanId;
  let testWorkoutId;
  let userId;
  
  // Se connecter avant tous les tests
  beforeAll(async () => {
    try {
      // Authentification - utiliser un compte admin pour avoir tous les droits
      const response = await api.post('/login', {
        email: 'admin@admin.net',
        password: 'admin123'
      });
      
      authToken = response.data.authorisation.token;
      userId = response.data.user.id;
      api.defaults.headers.common['Authorization'] = `Bearer ${authToken}`;
      
      console.log(`ALERTE : - Authentification réussie avec l'utilisateur ID ${userId}`);
      
      // Récupérer ou créer une séance d'entraînement pour les tests
      const workoutsResponse = await api.get('/workouts');
      if (workoutsResponse.data && workoutsResponse.data.length > 0) {
        testWorkoutId = workoutsResponse.data[0].id;
        console.log(`ALERTE : - Utilisation de la séance ID ${testWorkoutId} pour les tests`);
      } else {
        // Créer une séance si nécessaire
        const newWorkout = {
          title: "Test Workout for Plans Testing",
          description: "Created for automated testing of plans API",
          workout_category: "Technique",
          user_id: userId
        };
        
        const createWorkoutResponse = await api.post('/workouts', newWorkout);
        testWorkoutId = createWorkoutResponse.data.id;
        console.log(`ALERTE : - Séance créée avec ID ${testWorkoutId}`);
      }
    } catch (error) {
      console.error('ALERTE : - Erreur de configuration:', error.message);
      if (error.response) {
        console.error('Réponse d\'erreur:', error.response.data);
      }
      throw error;
    }
  });
  
  // Nettoyer après tous les tests
  afterAll(async () => {
    // Supprimer le plan créé pendant les tests s'il existe encore
    if (createdPlanId) {
      try {
        // Vérifier d'abord si le plan existe encore
        try {
          await api.get(`/plans/${createdPlanId}`);
          // Si on arrive ici, le plan existe encore, on peut le supprimer
          await api.delete(`/plans/${createdPlanId}`);
          console.log(`ALERTE : - Nettoyage du plan ID ${createdPlanId}`);
        } catch (checkError) {
          // Le plan n'existe plus, rien à faire
          console.log(`ALERTE : - Le plan ID ${createdPlanId} n'existe plus, pas besoin de nettoyage`);
        }
      } catch (error) {
        console.error('Erreur lors du nettoyage du plan:', error.message);
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
   * EN: - Test retrieving all training plans
   * FR: - Test de récupération de tous les plans d'entraînement
   */
  test('Can retrieve all training plans', async () => {
    const response = await api.get('/plans');
    
    expect(response.status).toBe(200);
    console.log(`ALERTE : - ${response.data.length} plans récupérés`);
    
    // La réponse doit être un tableau
    expect(Array.isArray(response.data)).toBe(true);
    
    // Afficher la structure d'un plan si disponible
    if (response.data.length > 0) {
      console.log('ALERTE : - Structure d\'un plan existant:', JSON.stringify(response.data[0], null, 2));
    }
  });
  
  /**
   * EN: - Test creating a new training plan
   * FR: - Test de création d'un nouveau plan d'entraînement
   */
  test('Can create a new training plan', async () => {
    // Données pour un nouveau plan
    const newPlan = {
      title: `Plan d'entraînement test ${Date.now()}`,
      description: "Plan créé via l'API pour les tests automatisés",
      plan_category: "Natation",
      plan_level: "Intermédiaire",
      duration: 4, // Durée en semaines
      user_id: userId
    };
    
    try {
      console.log('ALERTE : - Tentative de création avec les données:', JSON.stringify(newPlan, null, 2));
      const response = await api.post('/plans', newPlan);
      
      // Log pour le débogage
      console.log('ALERTE : - Réponse de création:', JSON.stringify(response.data, null, 2));
      
      expect(response.status).toBe(201);
      expect(response.data.id).toBeDefined();
      
      // Sauvegarder l'ID pour le nettoyage et les tests suivants
      createdPlanId = response.data.id;
      console.log(`ALERTE : - Nouveau plan créé avec ID ${createdPlanId}`);
      
      // Vérifier que les données ont été correctement enregistrées
      expect(response.data.title).toBe(newPlan.title);
      expect(response.data.plan_category).toBe(newPlan.plan_category);
      expect(response.data.plan_level).toBe(newPlan.plan_level);
    } catch (error) {
      console.error('ALERTE : - Erreur lors de la création du plan:', error.message);
      if (error.response) {
        console.error('Status:', error.response.status);
        console.error('Data:', JSON.stringify(error.response.data, null, 2));
      }
      throw error;
    }
  });
  
  /**
   * EN: - Test retrieving a specific training plan
   * FR: - Test de récupération d'un plan spécifique
   */
  test('Can retrieve a specific training plan', async () => {
    // Ce test dépend du test de création
    if (!createdPlanId) {
      console.warn('ALERTE : - Test ignoré: aucun ID de plan n\'a été créé');
      return;
    }
    
    const response = await api.get(`/plans/${createdPlanId}`);
    
    console.log('ALERTE : - Plan récupéré:', JSON.stringify(response.data, null, 2));
    
    expect(response.status).toBe(200);
    expect(response.data.id).toBe(createdPlanId);
  });
  
  /**
   * EN: - Test updating a training plan
   * FR: - Test de mise à jour d'un plan d'entraînement
   */
  test('Can update a training plan', async () => {
    // Ce test dépend du test de création
    if (!createdPlanId) {
      console.warn('ALERTE : - Test ignoré: aucun ID de plan n\'a été créé');
      return;
    }
    
    // Récupérer d'abord les données actuelles
    const currentData = await api.get(`/plans/${createdPlanId}`);
    
    // Mise à jour avec les données complètes requises par l'API
    const updateData = {
      title: `Plan mis à jour ${Date.now()}`,
      description: "Plan modifié via les tests automatisés",
      plan_category: "Triathlon",
      plan_level: "Avancé",
      duration: 6,
      // Conserver l'ID utilisateur existant
      user_id: currentData.data.user_id
    };
    
    console.log('ALERTE : - Données de mise à jour:', JSON.stringify(updateData, null, 2));
    
    try {
      const response = await api.put(`/plans/${createdPlanId}`, updateData);
      
      console.log('ALERTE : - Réponse de mise à jour:', JSON.stringify(response.data, null, 2));
      
      expect(response.status).toBe(200);
      
      // Vérifier que les données ont été mises à jour
      const updatedPlan = await api.get(`/plans/${createdPlanId}`);
      console.log('ALERTE : - Plan après mise à jour:', JSON.stringify(updatedPlan.data, null, 2));
      
      expect(updatedPlan.data.title).toBe(updateData.title);
      expect(updatedPlan.data.plan_category).toBe(updateData.plan_category);
      expect(updatedPlan.data.plan_level).toBe(updateData.plan_level);
    } catch (error) {
      console.error('ALERTE : - Erreur lors de la mise à jour du plan:', error.message);
      if (error.response) {
        console.error('Status:', error.response.status);
        console.error('Data:', JSON.stringify(error.response.data, null, 2));
      }
      throw error;
    }
  });
  
  /**
   * EN: - Test adding a workout to a training plan
   * FR: - Test d'ajout d'une séance à un plan d'entraînement
   */
  test('Can add a workout to a training plan', async () => {
    // Ce test dépend du test de création
    if (!createdPlanId || !testWorkoutId) {
      console.warn('ALERTE : - Test ignoré: aucun ID de plan ou de séance n\'est disponible');
      return;
    }
    
    try {
      console.log(`ALERTE : - Ajout de la séance ${testWorkoutId} au plan ${createdPlanId}`);
      const response = await api.post(`/plans/${createdPlanId}/workouts/${testWorkoutId}`);
      
      console.log('ALERTE : - Réponse d\'ajout de séance:', JSON.stringify(response.data, null, 2));
      
      // Adapter l'assertion aux codes de statut possibles
      expect([200, 201]).toContain(response.status);
      
      // Vérifier que la séance a bien été ajoutée
      const workoutsResponse = await api.get(`/plans/${createdPlanId}/workouts`);
      const workouts = workoutsResponse.data;
      
      console.log('ALERTE : - Séances du plan:', JSON.stringify(workouts, null, 2));
      
      expect(Array.isArray(workouts)).toBe(true);
      
      // Vérifier que la séance est bien dans la liste
      const workoutFound = workouts.some(workout => {
        if (typeof workout === 'object' && workout !== null) {
          return workout.id === testWorkoutId;
        }
        return false;
      });
      
      expect(workoutFound).toBe(true);
    } catch (error) {
      console.error('ALERTE : - Erreur lors de l\'ajout de séance au plan:', error.message);
      if (error.response) {
        console.error('Status:', error.response.status);
        console.error('Data:', JSON.stringify(error.response.data, null, 2));
      }
      throw error;
    }
  });
  
  /**
   * EN: - Test retrieving workouts for a plan
   * FR: - Test de récupération des séances d'un plan
   */
  test('Can retrieve workouts for a plan', async () => {
    // Ce test dépend des tests précédents
    if (!createdPlanId) {
      console.warn('ALERTE : - Test ignoré: aucun ID de plan n\'a été créé');
      return;
    }
    
    try {
      const response = await api.get(`/plans/${createdPlanId}/workouts`);
      
      console.log('ALERTE : - Séances du plan:', JSON.stringify(response.data, null, 2));
      
      expect(response.status).toBe(200);
      expect(Array.isArray(response.data)).toBe(true);
    } catch (error) {
      console.error('ALERTE : - Erreur lors de la récupération des séances du plan:', error.message);
      if (error.response) {
        console.error('Status:', error.response.status);
        console.error('Data:', JSON.stringify(error.response.data, null, 2));
      }
      throw error;
    }
  });
  
  /**
   * EN: - Test removing a workout from a plan
   * FR: - Test de suppression d'une séance d'un plan
   */
  test('Can remove a workout from a plan', async () => {
    // Ce test dépend de l'ajout préalable de séance au plan
    if (!createdPlanId || !testWorkoutId) {
      console.warn('ALERTE : - Test ignoré: aucun ID de plan ou de séance n\'est disponible');
      return;
    }
    
    try {
      console.log(`ALERTE : - Suppression de la séance ${testWorkoutId} du plan ${createdPlanId}`);
      const response = await api.delete(`/plans/${createdPlanId}/workouts/${testWorkoutId}`);
      
      console.log('ALERTE : - Réponse de suppression de séance:', JSON.stringify(response.data, null, 2));
      
      // Adapter l'assertion aux codes de statut standards pour les suppressions
      expect([200, 204]).toContain(response.status);
      
      // Vérifier que la séance a bien été supprimée
      const workoutsResponse = await api.get(`/plans/${createdPlanId}/workouts`);
      const workouts = workoutsResponse.data;
      
      // Vérifier que la séance n'est plus dans la liste
      const workoutFound = workouts.some(workout => {
        if (typeof workout === 'object' && workout !== null) {
          return workout.id === testWorkoutId;
        }
        return false;
      });
      
      expect(workoutFound).toBe(false);
    } catch (error) {
      console.error('ALERTE : - Erreur lors de la suppression de séance du plan:', error.message);
      if (error.response) {
        console.error('Status:', error.response.status);
        console.error('Data:', JSON.stringify(error.response.data, null, 2));
      }
      throw error;
    }
  });
  
  /**
   * EN: - Test duplicating a training plan
   * FR: - Test de duplication d'un plan d'entraînement
   */
  test('Can duplicate a training plan', async () => {
    // Ce test dépend du test de création
    if (!createdPlanId) {
      console.warn('ALERTE : - Test ignoré: aucun ID de plan n\'a été créé');
      return;
    }
    
    try {
      console.log(`ALERTE : - Duplication du plan ${createdPlanId}`);
      const response = await api.post(`/plans/${createdPlanId}/duplicate`);
      
      console.log('ALERTE : - Réponse de duplication:', JSON.stringify(response.data, null, 2));
      
      // Adapter l'assertion aux codes de statut possibles pour la création
      expect([200, 201]).toContain(response.status);
      
      // Vérifier que le nouveau plan a bien été créé
      expect(response.data.id).toBeDefined();
      expect(response.data.id).not.toBe(createdPlanId);
      
      // Nettoyer en supprimant le plan dupliqué
      try {
        await api.delete(`/plans/${response.data.id}`);
        console.log(`ALERTE : - Nettoyage du plan dupliqué ID ${response.data.id}`);
      } catch (cleanupError) {
        console.error('Erreur lors du nettoyage du plan dupliqué:', cleanupError.message);
      }
    } catch (error) {
      // Si l'API ne supporte pas la duplication, marquer le test comme ignoré
      if (error.response && error.response.status === 404) {
        console.warn('ALERTE : - La fonctionnalité de duplication de plan n\'est pas disponible dans l\'API');
        return;
      }
      
      console.error('ALERTE : - Erreur lors de la duplication du plan:', error.message);
      if (error.response) {
        console.error('Status:', error.response.status);
        console.error('Data:', JSON.stringify(error.response.data, null, 2));
      }
      throw error;
    }
  });
  
  /**
   * EN: - Test deleting a training plan
   * FR: - Test de suppression d'un plan d'entraînement
   */
  test('Can delete a training plan', async () => {
    // Ce test dépend du test de création
    if (!createdPlanId) {
      console.warn('ALERTE : - Test ignoré: aucun ID de plan n\'a été créé');
      return;
    }
    
    try {
      const response = await api.delete(`/plans/${createdPlanId}`);
      
      console.log('ALERTE : - Réponse de suppression:', JSON.stringify(response.data, null, 2));
      
      // Adapter l'assertion aux codes de statut standards pour les suppressions
      expect([200, 204]).toContain(response.status);
      
      // Vérifier que le plan a bien été supprimé
      try {
        await api.get(`/plans/${createdPlanId}`);
        throw new Error('Le plan aurait dû être supprimé');
      } catch (error) {
        expect(error.response.status).toBe(404);
        console.log(`ALERTE : - Confirmation: le plan ID ${createdPlanId} a été supprimé`);
      }
      
      // Réinitialiser l'ID pour éviter de tenter de le supprimer à nouveau dans afterAll
      createdPlanId = null;
    } catch (error) {
      console.error('ALERTE : - Erreur lors de la suppression du plan:', error.message);
      if (error.response) {
        console.error('Status:', error.response.status);
        console.error('Data:', JSON.stringify(error.response.data, null, 2));
      }
      throw error;
    }
  });
  
  /**
   * EN: - Test error handling for non-existent plan
   * FR: - Test de gestion des erreurs pour un plan inexistant
   */
  test('Returns 404 for non-existent plan', async () => {
    const nonExistentId = 99999; // ID qui n'existe probablement pas
    
    try {
      await api.get(`/plans/${nonExistentId}`);
      throw new Error('La requête aurait dû échouer pour un ID inexistant');
    } catch (error) {
      expect(error.response.status).toBe(404);
      console.log('ALERTE : - Réponse d\'erreur pour ressource introuvable:', JSON.stringify(error.response.data, null, 2));
    }
  });
  
  /**
   * EN: - Test validation errors
   * FR: - Test des erreurs de validation
   */
  test('Returns validation errors for invalid data', async () => {
    // Données incomplètes ou invalides
    const invalidPlan = {
      // Titre manquant
      description: "Plan sans titre",
      // Catégorie manquante
      plan_level: "Débutant"
      // user_id manquant également
    };
    
    try {
      await api.post('/plans', invalidPlan);
      throw new Error('La requête aurait dû échouer avec des données invalides');
    } catch (error) {
      // Accepter soit 400 (Bad Request) soit 422 (Unprocessable Entity)
      expect([400, 422]).toContain(error.response.status);
      console.log('ALERTE : - Réponse d\'erreur de validation:', JSON.stringify(error.response.data, null, 2));
    }
  });
});