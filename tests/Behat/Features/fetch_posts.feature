Feature: Get blog posts via an API
  In order to display posts from a blog
  As a front developer
  I need to be able to retrieve posts

  Scenario Outline: I can list all blog posts
    Given there are <number> posts
    When I request "/api/posts"
    Then the response code is 200
    And the response body contains JSON:
    """
    {
      "@context": "/api/contexts/Post",
      "@id": "/api/posts",
      "@type": "hydra:Collection",
      "hydra:totalItems": <number>,
      "hydra:member": [
          {
              "@type": "Post",
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

  Scenario: I get an empty array if no blog posts
    # database is purged before each scenario so no posts persisted
    When I request "/api/posts"
    Then the response code is 200
    And the response body contains JSON:
    """
    {
      "@context": "/api/contexts/Post",
      "@id": "/api/posts",
      "@type": "hydra:Collection",
      "hydra:totalItems": 0,
      "hydra:member": []
    }
    """

  Scenario: I can fetch one post by uuid
    Given there is a post with uuid "90ea65d7-dc1d-34ba-a824-b8c0f32cdc5f"
    And that post has title "A good title"

    When I request "/api/posts/90ea65d7-dc1d-34ba-a824-b8c0f32cdc5f"
    Then the response code is 200
    And the response body contains JSON:
    """
    {
        "@context": "\/api\/contexts\/Post",
        "@id": "\/api\/posts\/90ea65d7-dc1d-34ba-a824-b8c0f32cdc5f",
        "@type": "Post",
        "uuid": "90ea65d7-dc1d-34ba-a824-b8c0f32cdc5f",
        "title": "A good title",
        "content": "@variableType(string)",
        "summary": "@variableType(string)",
        "author": {
            "@type": "AuthorView",
            "name": "@variableType(string)"
        },
        "createdAt": "@variableType(string)",
        "updatedAt": "@variableType(string)"
    }
    """

  Scenario: I get a 404 http code if no post found
    Given there is a post with uuid "90ea65d7-dc1d-34ba-a824-b8c0f32cdc5f"
    When I request "/api/posts/90ea65d7-dc1d-34ba-a824-b8c0f32cd999"
    Then the response code is 404
