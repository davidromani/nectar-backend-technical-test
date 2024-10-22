Nectar Backend Technical Test v1.0
==================================

#### Installation requirements

 * Docker Compose 2.0+
 * Git 2.0+

#### Installation instructions

```bash
$ git clone git@github.com:davidromani/nectar-backend-technical-test.git nectar-backend
$ cd nectar-backend
$ make up
$ make install
$ make migrations
```

If it's necessary, remember to edit a `.env.local` config file according to your needs as a developer.

#### Testing suite commands

```bash
$ make testing
```

#### Usage notes

 * To show API docs open a browser [here](http://localhost:8741/api/docs)
 * To manage users & tasks as a ROLE_ADMIN inside a private backend open a browser [here](http://localhost:8741/admin) with username `nectar` and password `N3ct4r*`

#### Code Style notes

Execute following link to be sure that php-cs-fixer will be applied automatically before every commit. Please, check https://github.com/FriendsOfPHP/PHP-CS-Fixer to install it globally (manual) in your system.

```bash
$ ln -s ../../scripts/githooks/pre-commit .git/hooks/pre-commit
```
