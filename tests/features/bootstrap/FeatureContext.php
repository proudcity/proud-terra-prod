<?php

use Drupal\DrupalExtension\Context\RawDrupalContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawDrupalContext implements SnippetAcceptingContext {

  /**
   * Initializes context.
   *
   * Every scenario gets its own context instance.
   * You can also pass arbitrary arguments to the
   * context constructor through behat.yml.
   */
  public function __construct() {
  }



  /**
   * @When I wait for :arg1 seconds
   */
  public function iWaitForSeconds($arg1)
  {
    sleep($arg1);
  }

  // Wysiwyg functions from wetkit_test Drupal module


  public static function getAlias() {
    return 'wysiwyg';
  }

  
  /**
   * Get the instance variable to use in Javascript.
   *
   * @param string
   *   The instanceId used by the WYSIWYG module to identify the instance.
   *
   * @throws Exeception
   *   Throws an exception if the editor doesn't exist.
   *
   * @return string
   *   A Javascript expression representing the WYSIWYG instance.
   */
  protected function getWysiwygInstance($instanceId) {
    $instance = "CKEDITOR.instances['$instanceId']";

    if (!$this->getSession()->evaluateScript("return !!$instance")) {
      throw new \Exception(sprintf('The editor "%s" was not found on the page %s', $instanceId, $this->getSession()->getCurrentUrl()));
    }

    return $instance;
  }

  /**
   * Get a Mink Element representing the WYSIWYG toolbar.
   *
   * @param string
   *   The instanceId used by the WYSIWYG module to identify the instance.
   * @param string
   *   Identifies the underlying editor (for example, "tinymce").
   *
   * @throws Exeception
   *   Throws an exception if the toolbar can't be found.
   *
   * @return \Behat\Mink\Element\NodeElement
   *   The toolbar DOM Node.
   */
  protected function getWysiwygToolbar($instanceId, $editorType) {
    $driver = $this->getSession()->getDriver();

    if ($editorType == 'ckeditor') {
      $toolbarElement = $driver->find("//div[@id='cke_edit-body-und-0-value']");
      $toolbarElement = !empty($toolbarElement) ? $toolbarElement[0] : NULL;
    }
    else {
      $toolbarElement = $driver->find("//div[@id='{$instanceId}_toolbargroup']");
      $toolbarElement = !empty($toolbarElement) ? $toolbarElement[0] : NULL;
    }

    if (!$toolbarElement) {
      throw new \Exception(sprintf('Toolbar for editor "%s" was not found on the page %s', $instanceId, $this->getSession()->getCurrentUrl()));
    }

    return $toolbarElement;
  }

  /**
   * @When /^I type "([^"]*)" in the "([^"]*)" WYSIWYG editor$/
   */
  public function iTypeInTheWysiwygEditor($text, $instanceId) {
    $instance = $this->getWysiwygInstance($instanceId);
    $this->getSession()->executeScript("$instance.insertText(\"$text\");");
  }

  /**
   * @When /^I fill in the "([^"]*)" WYSIWYG editor with "([^"]*)"$/
   */
  public function iFillInTheWysiwygEditor($instanceId, $text) {
    $instance = $this->getWysiwygInstance($instanceId);
    $this->getSession()->executeScript("$instance.setData(\"$text\");");
  }

  /**
   * @When /^I click the "([^"]*)" button in the "([^"]*)" WYSIWYG editor$/
   */
  public function iClickTheButtonInTheWysiwygEditor($action, $instanceId) {
    $driver = $this->getSession()->getDriver();

    $instance = $this->getWysiwygInstance($instanceId);
    //$editorType = $this->getSession()->evaluateScript("return $instance.editor");
    //$toolbarElement = $this->getWysiwygToolbar($instanceId, $editorType);

    // Simulate click using ExecCommand.
    $this->getSession()->executeScript("CKEDITOR.instances[\"$instanceId\"].execCommand(\"$action\");");

    // Click the action button.
    //$button = $toolbarElement->find("xpath", "//a[starts-with(@title, '$action')]");
    //if (!$button) {
    //  throw new \Exception(sprintf('Button "%s" was not found on the page %s', $action, $this->getSession()->getCurrentUrl()));
    //}
    //$button->click();
    $driver->wait(1000, TRUE);
  }

  /**
   * @When /^I expand the toolbar in the "([^"]*)" WYSIWYG editor$/
   */
  public function iExpandTheToolbarInTheWysiwygEditor($instanceId) {
    $driver = $this->getSession()->getDriver();

    $instance = $this->getWysiwygInstance($instanceId);
    $editorType = $this->getSession()->evaluateScript("return $instance.editor");
    $toolbarElement = $this->getWysiwygToolbar($instanceId, $editorType);

    // TODO: This is tinyMCE specific. We should probably switch on
    // $editorType.
    $action = 'Show/hide toolbars';

    // Expand wysiwyg toolbar.
    $button = $toolbarElement->find("xpath", "//a[starts-with(@title, '$action')]");
    if (!$button) {
      throw new \Exception(sprintf('Button "%s" was not found on the page %s', $action, $this->getSession()->getCurrentUrl()));
    }
    if (strpos($button->getAttribute('class'), 'mceButtonActive') !== FALSE) {
      $button->click();
    }
  }

  /**
   * @Then /^I should see "([^"]*)" in the "([^"]*)" WYSIWYG editor$/
   */
  public function assertContentInWysiwygEditor($text, $tag, $region) {
    $instance = $this->getWysiwygInstance($instanceId);
    $content = $this->evaluateScript("return $instance.getData()");
    if (strpos($text, $content) === FALSE) {
      throw new \Exception(sprintf('The text "%s" was not found in the "%s" WYSWIYG editor on the page %s', $text, $instanceId, $this->getSession()->getCurrentUrl()));
    }
  }

  /**
   * @Then /^I should not see "([^"]*)" in the "([^"]*)" WYSIWYG editor$/
   */
  public function assertContentNotInWysiwygEditor($text, $tag, $region) {
    $instance = $this->getWysiwygInstance($instanceId);
    $content = $this->evaluateScript("return $instance.getData()");
    if (strpos($text, $content) !== FALSE) {
      throw new \Exception(sprintf('The text "%s" was found in the "%s" WYSWIYG editor on the page %s', $text, $instanceId, $this->getSession()->getCurrentUrl()));
    }
  }

}
