# status: DONE
# progress: 4/4
# updated: 2026-10-27
Feature: Local MCP server to create components and templates
  Scenario: Success — generate component markup
    When I call "generate-component" for "button" with variant "primary" and text "Save"
    Then I receive '<x-ui::button variant="primary">Save</x-ui::button>'

  Scenario: Success — generate a Livewire login page
    When I call "generate-template" for "auth.login" in mode "livewire"
    Then I receive a Blade page with "wire:submit" and a Livewire class with email, password and remember

  Scenario: Error — unknown prop
    When I call "generate-component" for "button" with the prop "varient"
    Then I receive an error that lists "variant"

  Scenario: Deviation — authoring outside the kit checkout
    Given the package is installed under "vendor/"
    When I list the tools
    Then "scaffold-component" and "scaffold-template" are not offered
