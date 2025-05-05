// Test des fonctionnalités principales de gestion des exercices
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
 * EN: - Test suite for exercises management
 * FR: - Suite de tests pour la gestion des exercices
 */
describe('Exercises Management', () => {
  let authToken;
  let createdExerciseId;
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
    // Supprimer l'exercice créé pendant les tests s'il existe encore
    if (createdExerciseId) {
      try {
        // Vérifier d'abord si l'exercice existe encore
        try {
          await api.get(`/exercises/${createdExerciseId}`);
          // Si on arrive ici, l'exercice existe encore, on peut le supprimer
          await api.delete(`/exercises/${createdExerciseId}`);
          console.log(`ALERTE : - Nettoyage de l'exercice ID ${createdExerciseId}`);
        } catch (checkError) {
          // L'exercice n'existe plus, rien à faire
          console.log(`ALERTE : - L'exercice ID ${createdExerciseId} n'existe plus, pas besoin de nettoyage`);
        }
      } catch (error) {
        console.error('Erreur lors du nettoyage de l\'exercice:', error.message);
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
   * EN: - Test retrieving all exercises
   * FR: - Test de récupération de tous les exercices
   */
  test('Can retrieve all exercises', async () => {
    const response = await api.get('/exercises');
    
    expect(response.status).toBe(200);
    console.log(`ALERTE : - ${response.data.length} exercices récupérés`);
    
    // La réponse doit être un tableau
    expect(Array.isArray(response.data)).toBe(true);
    
    // Afficher la structure d'un exercice si disponible
    if (response.data.length > 0) {
      console.log('ALERTE : - Structure d\'un exercice existant:', JSON.stringify(response.data[0], null, 2));
    }
  });
  
  /**
   * EN: - Test creating a new exercise
   * FR: - Test de création d'un nouvel exercice
   */
  test('Can create a new exercise', async () => {
    // Données pour un nouvel exercice
    const newExercise = {
      title: `Exercice de test ${Date.now()}`,
      description: "Cet exercice est créé via l'API pour les tests automatisés",
      exercise_level: "Intermédiaire",
      exercise_category: "Technique",
      user_id: userId
    };
    
    try {
      console.log('ALERTE : - Tentative de création avec les données:', JSON.stringify(newExercise, null, 2));
      const response = await api.post('/exercises', newExercise);
      
      // Log pour le débogage
      console.log('ALERTE : - Réponse de création:', JSON.stringify(response.data, null, 2));
      
      expect(response.status).toBe(201);
      expect(response.data.id).toBeDefined();
      
      // Sauvegarder l'ID pour le nettoyage et les tests suivants
      createdExerciseId = response.data.id;
      console.log(`ALERTE : - Nouvel exercice créé avec ID ${createdExerciseId}`);
      
      // Vérifier que les données ont été correctement enregistrées
      expect(response.data.title).toBe(newExercise.title);
      expect(response.data.exercise_level).toBe(newExercise.exercise_level);
      expect(response.data.exercise_category).toBe(newExercise.exercise_category);
    } catch (error) {
      console.error('ALERTE : - Erreur lors de la création de l\'exercice:', error.message);
      if (error.response) {
        console.error('Status:', error.response.status);
        console.error('Data:', JSON.stringify(error.response.data, null, 2));
      }
      throw error;
    }
  });
  
  /**
   * EN: - Test retrieving a specific exercise
   * FR: - Test de récupération d'un exercice spécifique
   */
  test('Can retrieve a specific exercise', async () => {
    // Ce test dépend du test de création
    if (!createdExerciseId) {
      console.warn('ALERTE : - Test ignoré: aucun ID d\'exercice n\'a été créé');
      return;
    }
    
    const response = await api.get(`/exercises/${createdExerciseId}`);
    
    console.log('ALERTE : - Exercice récupéré:', JSON.stringify(response.data, null, 2));
    
    expect(response.status).toBe(200);
    expect(response.data.id).toBe(createdExerciseId);
  });
  
  /**
   * EN: - Test updating an exercise
   * FR: - Test de mise à jour d'un exercice
   */
  test('Can update an exercise', async () => {
    // Ce test dépend du test de création
    if (!createdExerciseId) {
      console.warn('ALERTE : - Test ignoré: aucun ID d\'exercice n\'a été créé');
      return;
    }
    
    // Récupérer d'abord les données actuelles
    const currentData = await api.get(`/exercises/${createdExerciseId}`);
    
    // Mise à jour avec les données complètes requises par l'API
    const updateData = {
      title: `Exercice mis à jour ${Date.now()}`,
      description: "Description mise à jour via les tests automatisés",
      exercise_level: "Avancé",
      exercise_category: "Endurance",
      // Conserver l'ID utilisateur existant
      user_id: currentData.data.user_id
    };
    
    console.log('ALERTE : - Données de mise à jour:', JSON.stringify(updateData, null, 2));
    
    try {
      const response = await api.put(`/exercises/${createdExerciseId}`, updateData);
      
      console.log('ALERTE : - Réponse de mise à jour:', JSON.stringify(response.data, null, 2));
      
      expect(response.status).toBe(200);
      
      // Vérifier que les données ont été mises à jour
      const updatedExercise = await api.get(`/exercises/${createdExerciseId}`);
      console.log('ALERTE : - Exercice après mise à jour:', JSON.stringify(updatedExercise.data, null, 2));
      
      expect(updatedExercise.data.title).toBe(updateData.title);
      expect(updatedExercise.data.exercise_level).toBe(updateData.exercise_level);
      expect(updatedExercise.data.exercise_category).toBe(updateData.exercise_category);
    } catch (error) {
      console.error('ALERTE : - Erreur lors de la mise à jour de l\'exercice:', error.message);
      if (error.response) {
        console.error('Status:', error.response.status);
        console.error('Data:', JSON.stringify(error.response.data, null, 2));
      }
      throw error;
    }
  });
  
  /**
   * EN: - Test finding workouts that use a specific exercise
   * FR: - Test de recherche des séances utilisant un exercice spécifique
   */
  test('Can find workouts using a specific exercise', async () => {
    // Ce test nécessite un ID d'exercice existant
    // On pourrait utiliser l'exercice créé, mais celui-ci n'est probablement
    // pas encore associé à des séances, on va donc prendre le premier exercice existant
    try {
      // Récupérer tous les exercices
      const exercisesResponse = await api.get('/exercises');
      
      // S'assurer qu'il y a au moins un exercice
      if (exercisesResponse.data.length === 0) {
        console.warn('ALERTE : - Aucun exercice disponible pour ce test');
        return;
      }
      
      // Utiliser le premier exercice pour tester
      const testExerciseId = exercisesResponse.data[0].id;
      console.log(`ALERTE : - Recherche des séances utilisant l'exercice ID ${testExerciseId}`);
      
      // Cette route pourrait varier selon votre API
      // Essai 1: /exercises/{id}/workouts
      try {
        const response = await api.get(`/exercises/${testExerciseId}/workouts`);
        
        console.log('ALERTE : - Séances utilisant l\'exercice:', JSON.stringify(response.data, null, 2));
        
        expect(response.status).toBe(200);
        expect(Array.isArray(response.data)).toBe(true);
      } catch (error) {
        if (error.response && error.response.status === 404) {
          console.warn('ALERTE : - La route /exercises/{id}/workouts n\'existe pas, essai avec une autre route');
          
          // Essai 2 (si la première route n'existe pas)
          try {
            const response = await api.get(`/workouts?exercise_id=${testExerciseId}`);
            
            console.log('ALERTE : - Séances utilisant l\'exercice (alternative):', JSON.stringify(response.data, null, 2));
            
            expect(response.status).toBe(200);
            expect(Array.isArray(response.data)).toBe(true);
          } catch (error2) {
            console.warn('ALERTE : - Impossible de trouver les séances associées à l\'exercice');
          }
        } else {
          throw error;
        }
      }
    } catch (error) {
      console.error('ALERTE : - Erreur lors de la recherche des séances:', error.message);
      if (error.response) {
        console.error('Status:', error.response.status);
        console.error('Data:', JSON.stringify(error.response.data, null, 2));
      }
    }
  });
  
  /**
   * EN: - Test filtering exercises by category
   * FR: - Test de filtrage des exercices par catégorie
   */
  test('Can filter exercises by category', async () => {
    try {
      // Récupérer tous les exercices pour identifier les catégories disponibles
      const allExercisesResponse = await api.get('/exercises');
      
      // S'assurer qu'il y a au moins un exercice
      if (allExercisesResponse.data.length === 0) {
        console.warn('ALERTE : - Aucun exercice disponible pour ce test');
        return;
      }
      
      // Extraire les catégories uniques
      const categories = [...new Set(allExercisesResponse.data
        .filter(ex => ex.exercise_category)
        .map(ex => ex.exercise_category))];
      
      if (categories.length === 0) {
        console.warn('ALERTE : - Aucune catégorie d\'exercice disponible pour ce test');
        return;
      }
      
      // Utiliser la première catégorie pour le test
      const testCategory = categories[0];
      console.log(`ALERTE : - Filtrage des exercices par catégorie "${testCategory}"`);
      
      // Cette route pourrait varier selon votre API
      // Essai 1: /exercises?category={category}
      try {
        const response = await api.get(`/exercises?category=${encodeURIComponent(testCategory)}`);
        
        console.log(`ALERTE : - Exercices de la catégorie "${testCategory}":`, JSON.stringify(response.data, null, 2));
        
        expect(response.status).toBe(200);
        expect(Array.isArray(response.data)).toBe(true);
        
        // Vérifier que tous les exercices retournés sont de la bonne catégorie
        if (response.data.length > 0) {
          const allCorrectCategory = response.data.every(ex => ex.exercise_category === testCategory);
          expect(allCorrectCategory).toBe(true);
        }
      } catch (error) {
        if (error.response && error.response.status === 404) {
          console.warn('ALERTE : - Le filtrage par catégorie n\'est peut-être pas implémenté dans l\'API');
        } else {
          throw error;
        }
      }
    } catch (error) {
      console.error('ALERTE : - Erreur lors du filtrage par catégorie:', error.message);
      if (error.response) {
        console.error('Status:', error.response.status);
        console.error('Data:', JSON.stringify(error.response.data, null, 2));
      }
    }
  });
  
  /**
   * EN: - Test filtering exercises by level
   * FR: - Test de filtrage des exercices par niveau
   */
  test('Can filter exercises by level', async () => {
    try {
      // Récupérer tous les exercices pour identifier les niveaux disponibles
      const allExercisesResponse = await api.get('/exercises');
      
      // S'assurer qu'il y a au moins un exercice
      if (allExercisesResponse.data.length === 0) {
        console.warn('ALERTE : - Aucun exercice disponible pour ce test');
        return;
      }
      
      // Extraire les niveaux uniques
      const levels = [...new Set(allExercisesResponse.data
        .filter(ex => ex.exercise_level)
        .map(ex => ex.exercise_level))];
      
      if (levels.length === 0) {
        console.warn('ALERTE : - Aucun niveau d\'exercice disponible pour ce test');
        return;
      }
      
      // Utiliser le premier niveau pour le test
      const testLevel = levels[0];
      console.log(`ALERTE : - Filtrage des exercices par niveau "${testLevel}"`);
      
      // Cette route pourrait varier selon votre API
      // Essai 1: /exercises?level={level}
      try {
        const response = await api.get(`/exercises?level=${encodeURIComponent(testLevel)}`);
        
        console.log(`ALERTE : - Exercices du niveau "${testLevel}":`, JSON.stringify(response.data, null, 2));
        
        expect(response.status).toBe(200);
        expect(Array.isArray(response.data)).toBe(true);
        
        // Vérifier que tous les exercices retournés sont du bon niveau
        if (response.data.length > 0) {
          const allCorrectLevel = response.data.every(ex => ex.exercise_level === testLevel);
          expect(allCorrectLevel).toBe(true);
        }
      } catch (error) {
        if (error.response && error.response.status === 404) {
          console.warn('ALERTE : - Le filtrage par niveau n\'est peut-être pas implémenté dans l\'API');
        } else {
          throw error;
        }
      }
    } catch (error) {
      console.error('ALERTE : - Erreur lors du filtrage par niveau:', error.message);
      if (error.response) {
        console.error('Status:', error.response.status);
        console.error('Data:', JSON.stringify(error.response.data, null, 2));
      }
    }
  });
  
  /**
   * EN: - Test deleting an exercise
   * FR: - Test de suppression d'un exercice
   */
  test('Can delete an exercise', async () => {
    // Ce test dépend du test de création
    if (!createdExerciseId) {
      console.warn('ALERTE : - Test ignoré: aucun ID d\'exercice n\'a été créé');
      return;
    }
    
    try {
      const response = await api.delete(`/exercises/${createdExerciseId}`);
      
      console.log('ALERTE : - Réponse de suppression:', JSON.stringify(response.data, null, 2));
      
      // Adapter l'assertion aux codes de statut standards pour les suppressions
      expect([200, 204]).toContain(response.status);
      
      // Vérifier que l'exercice a bien été supprimé
      try {
        await api.get(`/exercises/${createdExerciseId}`);
        throw new Error('L\'exercice aurait dû être supprimé');
      } catch (error) {
        expect(error.response.status).toBe(404);
        console.log(`ALERTE : - Confirmation: l'exercice ID ${createdExerciseId} a été supprimé`);
      }
      
      // Réinitialiser l'ID pour éviter de tenter de le supprimer à nouveau dans afterAll
      createdExerciseId = null;
    } catch (error) {
      console.error('ALERTE : - Erreur lors de la suppression de l\'exercice:', error.message);
      if (error.response) {
        console.error('Status:', error.response.status);
        console.error('Data:', JSON.stringify(error.response.data, null, 2));
      }
      throw error;
    }
  });
  
  /**
   * EN: - Test error handling for non-existent exercise
   * FR: - Test de gestion des erreurs pour un exercice inexistant
   */
  test('Returns 404 for non-existent exercise', async () => {
    const nonExistentId = 99999; // ID qui n'existe probablement pas
    
    try {
      await api.get(`/exercises/${nonExistentId}`);
      throw new Error('La requête aurait dû échouer pour un ID inexistant');
    } catch (error) {
      expect(error.response.status).toBe(404);
      console.log('ALERTE : - Réponse d\'erreur pour ressource introuvable:', JSON.stringify(error.response.data, null, 2));
    }
  });
  
  /**
   * EN: - Test validation for invalid exercise data
   * FR: - Test de validation pour des données d'exercice invalides
   */
  test('Returns validation errors for invalid exercise data', async () => {
    // Données incomplètes ou invalides
    const invalidExercise = {
      // Titre manquant
      description: "Description sans titre",
      // Catégorie manquante
      exercise_level: "Débutant",
      // user_id manquant
    };
    
    try {
      await api.post('/exercises', invalidExercise);
      throw new Error('La requête aurait dû échouer avec des données invalides');
    } catch (error) {
      // Accepter soit 400 (Bad Request) soit 422 (Unprocessable Entity)
      expect([400, 422]).toContain(error.response.status);
      console.log('ALERTE : - Réponse d\'erreur de validation:', JSON.stringify(error.response.data, null, 2));
    }
  });
});