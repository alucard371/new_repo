Feature: Search
  In order to find last order
  As a customer
  I need to be able to search for orders

  @javascript
  Scenario: Search for an order that exists
    Given   I am on "/"
    When    I fill in "Récuperation" with "balohe37@gmail.com"
    And     I press "searchSubmit"
    Then    I should see "Un nouveau mail vous à été envoyé"