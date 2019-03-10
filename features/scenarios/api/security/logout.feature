@api
@api_logout

Feature: I need to be able to logout from mobile application & remove refreshToken from database
  Background:
    Given I load following users:
      | username      | password | firstname | lastname | tokenActivation |
      | john@doe.com  | 12345678 | John      | Doe      | TOKENACTIVATION |
    And I enable user "john@doe.com"
  Scenario: [Fail] Invalid payload
    When I send a "POST" request to "/api/logout" with body:
    """
    {
    }
    """
    Then the response status code should be 400
    And the JSON node "refreshToken" should exist

  Scenario: [Success] Success remove refresh token on logout
    When After authentication with "john@doe.com" and password "12345678", I try to logout to "/api/logout" with POST request
    Then the response status code should be 204
    And the user "john@doe.com" should not have a refresh token
