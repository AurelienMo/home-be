@api
@api_registration

Feature: As an anonymous user, I need to be able to send registration request
  Background:
    Given I load following users:
      | username      | password | firstname | lastname | tokenActivation |
      | john@doe.com  | 12345678 | John      | Doe      | TOKENACTIVATION |
    And I enable user "john@doe.com"

  Scenario: [Fail] Submit request with auth user
    When After authentication on url "/api/login_check" with method "POST" as user "john@doe.com" with password "12345678", I send a "POST" request to "/api/registration" with body:
    """
    {
        "username": "janedoe@yopmail.com",
        "password": "12345678",
        "firstName": "Jane",
        "lastName": "Doe"
    }
    """
    Then the response status code should be 403
    And the JSON node "message" should be equal to "Vous ne pouvez pas vous inscrire en étant connecté."

  Scenario: !

  Scenario: [Fail] Submit request with invalid email & too short password
    When I send a "POST" request to "/api/registration" with body:
    """
    {
        "username": "janedoe",
        "password": "12345",
        "firstName": "Jane",
        "lastName": "Doe"
    }
    """
    Then the response status code should be 400
    And the JSON node "username" should exist
    And the JSON node "password" should exist

  Scenario: [Fail] Submit request with weak password
    When I send a "POST" request to "/api/registration" with body:
    """
    {
        "username": "jane@doe.com",
        "password": "123456789",
        "firstName": "Jane",
        "lastName": "Doe"
    }
    """
    Then the response status code should be 400
    And the JSON node "password[0]" should be equal to "Votre mot de passe doit contenir au minimum 8 caracteres, 1 majuscule ainsi qu'un caractere special"

  Scenario: [Fail] Submit request with already exist user
    When I send a "POST" request to "/api/registration" with body:
    """
    {
        "username": "john@doe.com",
        "password": "Azerty34!",
        "firstName": "John",
        "lastName": "Doe"
    }
    """
    Then the response status code should be 400
    And the JSON should be equal to:
    """
    {
        "": [
            "Cet utilisateur existe déjà."
        ]
    }
    """

  Scenario: [Success] Submit request with valid datas
    When I send a "POST" request to "/api/registration" with body:
    """
    {
        "username": "jane@doe.com",
        "password": "Azerty34!",
        "firstName": "Jane",
        "lastName": "Doe"
    }
    """
    Then the response status code should be 201
    And 1 mails should have been sent
    And user with username "jane@doe.com" should exist into database
    And user with username "jane@doe.com" should have status equal to "pending_validation"
    And the user "jane@doe.com" should have a token activation
