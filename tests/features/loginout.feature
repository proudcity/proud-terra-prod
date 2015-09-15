Feature: Log in and out of the site
  In order to maintain an account
    As a site visitor
    I need to log in and out of the site.


# Require a real browser. Will use Selenium/Firefox (or Zombie or Sahi).
@api
@javascript
Scenario: Editor can use angular_media in CKEditor
 When I am logged in as a user with the "administrator" role
    And I am on "/node/add/page"
    And I click the "image" button in the "edit-body-und-0-value" WYSIWYG editor
    And I wait for 1 seconds
    And I press "Upload/Browse Server"
  Then I should see "Drop files here"


@api
Scenario: Create nodes with specific authorship
  And "article" content:
  | title          | body             | promote |
  | Article by Joe | PLACEHOLDER BODY | 1       |
  When I am logged in as a user with the "administrator" role
  And I am on the homepage
  And I follow "Article by Joe"
  Then I should see the link "Joe User"