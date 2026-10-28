# status: DONE
# progress: 3/3
# updated: 2026-10-27
Feature: Skill folder with an example for every component, template and layout
  Scenario: Success — every example renders
    Given the folder "skill/examples"
    When every example is rendered with Blade in English
    Then 96 examples render without exception

  Scenario: Error — undocumented component
    Given a component file that no reference claims
    When the coverage check runs
    Then it fails and names the component

  Scenario: Deviation — example uses a raw color
    Given an example with the class "bg-red-500"
    When the token lint runs
    Then it fails and names the file and the class
