# Blog service

Provides an API to manage the posts of a blog.   
Test project to build an API with symfony with a [DDD](https://en.wikipedia.org/wiki/Domain-driven_design) approach and using [CQS](https://en.wikipedia.org/wiki/Command%E2%80%93query_separation) principle.  
It also uses the API framework API Platform

## Getting started

### Prerequisites

- [Make](https://www.gnu.org/software/make)
- [Symfony cli](https://symfony.com/download)

### Installation

First, clone and move into the repository:
``` bash
$ git clone https://github.com/quentbrd/blog_ddd.git
```

Setup a mariadb database or any relational database and configure access:
``` bash
DATABASE_URL="mysql://admin:admin@127.0.0.1:3306/blog"
```

Finally start server:
``` bash
make server-start
```

Your API documentation should be visible here : http://localhost:8000/api

### Makefile

The `Makefile` provides several commands to ease the development.
You can see the list of all available commands by running:

``` bash
$ make help
```

## Going further
- [Tests and tooling](docs/test_and_tools.md)

