Connections
===========

Connections is a project where I tried to bring together some 
patterns I wanted to properly use in a project for some time now.

- Two separated projects that although they live in the same
repository, then can be deployed in different servers and be
maintained, scaled and further developed independently.

- Mostly Domain Oriented, expressing as much as possible the language
of the domain in the codebase.

- Clean-ish architecture, with separations between Service Layers,
Domain Models and Infrastructure concerns.

- Strongly-typed Collections (called `List` in the code), for better
handling of Domain concepts rather than pure arrays.

- Controllers can be reused with any other framework in a really easy
way.

- Monorepo, hosting two separate projects that make use of the same 
dependencies. This aspect can be greatly improved.

- Highly composable, the project makes use of several components that
are small and fast enough so support high traffic volumes.

- Features a Dependency Injection container (php-di), a PSR-15 Request 
Handler (relay), PSR-7 Implementation (Zend Diactoros), an extremely
fast Router (Fast Router), support for templates (twig) and makes use 
an Api client to consume information (guzzle)

## Installing Connections

After cloning the repository, there are just a few commands needed to
get up and running.

First, install composer
```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

Then, run composer to install the required dependencies
```bash
# Install Composer
php composer.phar install
```

Finally, just run the scripts that sets up the database with some 
sample data:
```bash
./bin/generateSchema
```

And that's all.

## Running two servers

Connections is composed of two separated servers. This is to demonstrate
how the two are actually `different Bounded Contexts` that are not
entirely related to each other, but can share common understanding
through an `Anticorruption Layer`. As an example, we can see in the code
that both projects share the name of a `UserId` concept, but it means
entirely different things to both of them.

One server runs the Api endpoint that provides access to the 
Connections between users. To run this server, you can use the 
commmand for it: 

```bash
./bin/startApi
```

The second package runs a really small website that shows a list of 
users assigned to a group, and their relationships with other users.
To run this website, just `open a new terminal window and execute:
```bash
./bin/startWebsite
```

And head up to `http://localhost:8080/`.

## Api Endpoints

The Api presents three endpoints to the outside world.

#### POST /api/connection/

A POST call to this endpoint will generate a Connection Request 
between two users. The body should look like: 

```json
{
    "userFrom": "63e82b8b-92e9-42c3-8ee2-0ccadeb454ee",
    "userTo": "7d038889-3989-4e74-8e47-754f3508c994"
}
```

The service will return a `201 Created` status code on success, or 
a `409 Conflict` if this relation request already exists. POST isn't
and idempotent operation, but in this case making several request 
will work only the first time.
If the User Ids are not proper UUID, then the service will return 
`400 Bad Request`. Under any other erroneus situation, then it will
be a `500 Interal Server Error`.

#### POST /api/connection/accept

A POST call to this endpoint will accept a previous Connection Request.
The body of the call should be the same as the previous example and
the service will previously check that a proper Connection Request
exists before attempting to create a Relationship Connection. 

At the same time, whenever we approve a Connection Request, then 
this Relationship Connection is built both ways, so both users being
part of this process will have the other user as a confirmed Connection.

As like the previous method, this endpoint will return `201 Created`
on success, `400 Bad Request` if User Ids are malformed, `409 Conflict` 
if we can' find a previous Connection Request, or `500 Internal Server Error`
for everything else.

#### GET /api/connection/{userId}

A GET Request here will return currently all the Relationship 
Connections this user has. That means, connections requests that 
have been previously approved. It won't show Connection Requests or
Declined Connections.

This decision was made in order to support a rational working of the
website that consumes this infomation, but the different conditions
to load the different kinds of Connections is included in the code
and just a matter to implement different GET endpoints that filter
on those.

If the user has no Relationship Connections with any user, the endpoint
will return `404 Not Found`. As per the previous endpoints, it will 
return `400 Bad Request` if User ids are not properly formated as UUIDs,
and `500 Internal Server Error` if something else happens.

## The Website

Certainly is a farily simplification of what a `website` should be, but 
it's working under the same tools and implementations as the API. 
Consisting also of a Middleware Request Handler, with a Depedency
Injection Container to do the proper wiring, the website presents 
different requirements than the API.

It's so that it features Twig as a templating engine and Guzzle as the
Api Client that requests information to the Connections API.

#### Service Layer in the Website

There's quite a noticeable differente in the Application Services from
the API and the Website, and it's due to the fact that they serve
different purposes.

While the Service Layer in the API deals mosting with Domain objects
coming in and getting out, the Website Service Layer deals mostly with
native arrays of composed information, specifically crafted for the 
needs of the Templates. 

This is so to reflect that a Service Layer in the website should mostly
be reading from created projections that hold all the necessary information
for the page we'e looking at, and so the models should reflect that. 