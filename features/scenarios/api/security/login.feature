@api
@api_login

Feature: As an anonymous user, I need to be able to submit login request
  Scenario: [Fail] Invalid credentials
    When Send auth request with method "POST" request to "/api/login_check" with username "john" and password "12345678"
    Then the response status code should be 401
    And the JSON node "code" should be equal to "401"
    And the JSON node "message" should be equal to "Identifiants invalides."

  Scenario: [Success] Successful login
    Given I load following users:
    | username      | password | firstname | lastname | tokenActivation |
    | john@doe.com  | 12345678 | John      | Doe      | TOKENACTIVATION |
    And I enable user "john@doe.com"
    When Send auth request with method "POST" request to "/api/login_check" with username "john@doe.com" and password "12345678"
    Then the response status code should be 200
    And the JSON node "token" should exist
    And the JSON node "user" should exist
    And the JSON node "user.firstName" should be equal to "John"
    And the JSON node "user.lastName" should be equal to "Doe"
    And the JSON node "user.username" should be equal to "john@doe.com"
    And the JSON node "user.roles" should exist
    And the JSON node "refresh_token" should exist
