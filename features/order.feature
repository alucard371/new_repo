Feature: Order
  In order to order tickets
  As a customer
  I need to be able to make an order

  @javascript
  Scenario: Order tickets to visit Le Louvre
    Given   I am on "/"
    When    I fill in "Email" with "balohe37@gmail.com"
    And     I fill in "Date de la visite" with "12/O6/2017"
    And     I press "addTicket"
    And     I fill in "Nom" with "moi"
    And     I fill in "Prénom" with "même"
    And     I fill in "Date de naissance" with "12/O6/1981"
    And     I press "validate"
    Then    I should be on "recapitulatif"
    And     I should see "Votre commande est enregistrée"
    And     I press "customButton"
    And     I fill in "Adresse e-mail" with "balohe37@gmail.com"
    And     I fill in "Numéro de carte" with "4242424242424242"
    And     I fill in "MM / AA" with "0919"
    And     I fill in "CVV" with "090"
    And     I press "submitButton"
    Then    I should be on "checkout"
    And     I should see "Votre commande est maintenant terminée."
    And     I press "endOrder"
    Then    I should be on "/"

