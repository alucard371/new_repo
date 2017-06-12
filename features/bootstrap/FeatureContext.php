<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given |I am on |:arg1
     */
    public function iAmOn($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When |I fill in :arg1 with :arg2
     */
    public function iFillInWith($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @When |I press :arg1
     */
    public function iPress($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then |I should see text matching :arg1
     */
    public function iShouldSeeTextMatching($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When I click on :arg1
     */
    public function iClickOn($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When I press on :arg1
     */
    public function iPressOn($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When I fill in :arg1 from :arg2
     */
    public function iFillInFrom($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @When I select :arg1 with :arg2
     */
    public function iSelectWith($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @Then I should be on recapitulatif
     */
    public function iShouldBeOnRecapitulatif()
    {
        throw new PendingException();
    }








}
