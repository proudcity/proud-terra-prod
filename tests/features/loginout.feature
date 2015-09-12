Feature: Log in and out of the site
  In order to maintain an account
    As a site visitor
    I need to log in and out of the site.


# Require a real browser. Will use Selenium/Firefox (or Zombie or Sahi).
@javascript
Scenario: Logs out of the site
  Given I am on "/user"
  When I fill in "Username" with "test"
    And I fill in "Password" with "test"
    And I press "Log in"
    And I am on "/node/add/page"
    And I click the "image" button in the "edit-body-und-0-value" WYSIWYG editor
    And I wait for 1 seconds
    And I press "Upload/Browse Server"
  Then I should see "Drop files here"



