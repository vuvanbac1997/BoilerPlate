## Use Generators

On this boilerplate, I added many generators

Use `make:new-model` instead of `make:model` and use `make:repository`, `make:service`,`make:helper` to create repositories, services, helpers.
And `make:admin-crud` to create admin crud.

The process for setting up the base structure will be following.

1. You can create migration with `make:migration` and create the tables
2. Create model with `make:new-model`
3. Create repository with `make:repository`
4. Create Admin CRUD with `make:admin-crud`
5. Create services and helpers with `make:service` and `make:helper` if needed.

These generators create test code also. You need to add more tests on these files.
