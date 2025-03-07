# Contexte du projet
Face à l’importance de la gestion financière personnelle, SaveSmart se présente comme un outil simple et efficace pour aider chacun à maîtriser ses finances. Ce projet s’inscrit dans un cursus de niveau intermédiaire, alliant la mise en pratique des compétences Laravel et la gestion de projet en mode agile.

## Objectifs du projet

### Fonctionnels
- Permettre l’inscription/authentification sécurisée des utilisateurs. (S1)
- Ajout de plusieurs utilisateurs sous un même compte familial. (S1)
- Gérer la saisie et le suivi des revenus, dépenses et objectifs financiers via des formulaires CRUD. (S1)
- Offrir des visualisations graphiques simples (tableaux, diagrammes) pour illustrer la répartition du budget. (S1)
- Ajout de catégories personnalisables (ex. Alimentation, Logement, Divertissement, Épargne). (S1)
- Création d’objectifs d’épargne (ex. Acheter un PC, Partir en vacances). (S2)
- Affichage de la progression par rapport aux montants économisés. (S2)
- Développer un algorithme d’optimisation budgétaire (basé sur des règles logiques et non sur l’IA) qui propose une répartition du budget en fonction des priorités définies (ex. besoins, envies, épargne). (S2)
- Ajout méthodes d’optimisation 50/30/20 (Besoins 50% / Envies 30% / Épargne 20%). (S2)

# Project Overview
This project is a web application designed for managing user accounts, profiles, transactions, saving goals, and categories. It provides functionalities such as user authentication, profile management, and financial tracking.

## Installation Instructions
1. Clone the repository.
2. Run `composer install` to install dependencies.
3. Set up the `.env` file with the necessary environment variables.
4. Run migrations with `php artisan migrate`.
5. Start the server using `php artisan serve`.

## Usage
- Access the application via the web browser at `http://localhost:8000`.
- Users can register, log in, and manage their profiles.
- Users can create, update, and delete transactions and saving goals.

## File Structure
- **routes/web.php**: Defines the web routes for the application, including routes for user authentication, profile management, transactions, and saving goals.
- **app/Http/Controllers/ProfileController.php**: Handles the logic for profile management, including creating, updating, and deleting profiles.

## API Documentation
### Authentication Routes
- `GET /login`: Displays the login form.
- `POST /login`: Authenticates the user.
- `GET /signup`: Displays the signup form.
- `POST /signup`: Registers a new user.
- `POST /logout`: Logs out the user.

### Profile Routes
- `GET /profile/create`: Displays the form to create a new profile.
- `PUT /profile/create`: Stores a new profile.
- `GET /profile/{profile}`: Displays the specified profile.
- `PATCH /profile/{id}/edit`: Updates the specified profile.
- `DELETE /profile/{profile}/edit`: Deletes the specified profile.

### Transaction Routes
- `PUT /transaction/store`: Stores a new transaction.
- `PATCH /transaction/{transaction}/edit`: Updates a transaction.
- `DELETE /transaction/{transaction}/edit`: Deletes a transaction.

### Saving Goal Routes
- `PUT /goal/store`: Stores a new saving goal.
- `PATCH /goal/{goal}/edit`: Updates a saving goal.
- `DELETE /goal/{goal}/edit`: Deletes a saving goal.

### Category Routes
- `PUT /category/store`: Stores a new category.
- `DELETE /category/{category}/edit`: Deletes a category.

## Contributing
Contributions are welcome! Please fork the repository and submit a pull request with your changes.

## License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
