@api
@api_contact

Feature: I need to be able to send a contact request
  Scenario: [Fail] Submit request with no payload
    When I send a "POST" request to "/api/contact" with body:
    """
    {
    }
    """
    Then the response status code should be 400
    And the JSON node "firstname" should have "1" element
    And the JSON node "lastname" should have "1" element
    And the JSON node "email" should have "1" element
    And the JSON node "subject" should have "1" element
    And the JSON node "message" should have "1" element
    And the response should be equal to following file "Output/api/common/contact/contact_no_payload.json"
