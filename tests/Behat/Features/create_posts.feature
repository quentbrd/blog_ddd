Feature: Create blog posts via an API
  In order to create posts for a blog
  As a front developer
  I need to be able to persist posts

  Background:
    Given there is an author with uuid "80a13cdb-2940-4f1f-bd7d-756368e2424e"
    And that author has name "John Smith"

  Scenario: I can create a blog post
    Given the request body is:
    """
    {
      "title": "Great title",
      "content": "super long content",
      "summary": "post summary",
      "authorUuid": "80a13cdb-2940-4f1f-bd7d-756368e2424e"
    }
    """
    And the "Content-Type" request header is "application/ld+json"
    When I request "/api/posts" using HTTP "POST"
    Then the response code is 201
    And the response body contains JSON:
    """
    {
      "@context": "\/api\/contexts\/Post",
      "@type": "Post",
      "uuid": "@variableType(string)",
      "title": "Great title",
      "content": "super long content",
      "summary": "post summary",
      "author": {
        "name": "John Smith"
      },
      "createdAt": "@variableType(string)",
      "updatedAt": "@variableType(string)"
    }
    """

  Scenario: I cannot create a blog post if author doesn't exist
    Given the request body is:
    """
    {
      "title": "Great title",
      "content": "super long content",
      "summary": "post summary",
      "authorUuid": "80a13cdb-2940-4f1f-bd7d-756368e2424f"
    }
    """
    And the "Content-Type" request header is "application/ld+json"
    When I request "/api/posts" using HTTP "POST"
    Then the response code is 422

 Scenario Outline: I cannot create a blog post one field is empty
    Given the request body is:
    """
    {
      <title>
      "content": "super long content",
      "summary": "post summary",
      "authorUuid": "80a13cdb-2940-4f1f-bd7d-756368e2424f"
    }
    """
    And the "Content-Type" request header is "application/ld+json"
    When I request "/api/posts" using HTTP "POST"
    Then the response code is 400

  Examples:
      |title       |
      |"title": "",|
      |            |