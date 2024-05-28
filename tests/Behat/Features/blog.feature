Feature: Manage blog posts via an API
  In order to manage posts from a blog
  As a front developer
  I need to be able to create, retrieve, update and delete posts

  Scenario Outline: I can list all blog posts
    Given there are <number> posts
    When I request "/api/posts"
    Then the response code is 200
    And the response body contains JSON:
    """
    {
      "@context": "/api/contexts/BlogPost",
      "@id": "/api/posts",
      "@type": "hydra:Collection",
      "hydra:totalItems": <number>,
      "hydra:member": [
          {
              "@type": "BlogPost",
              "uuid": "@variableType(string)",
              "title": "@variableType(string)",
              "summary": "@variableType(string)",
              "author": {
                  "@type": "AuthorView",
                  "name": "@variableType(string)"
              },
              "createdAt": "@variableType(string)",
              "updatedAt": "@variableType(string)"
          }
      ]
    }
    """
  Examples:
    |number|
    | 1    |
    | 5    |
    | 10   |
