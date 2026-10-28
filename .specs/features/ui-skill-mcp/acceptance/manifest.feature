# status: DONE
# progress: 3/3
# updated: 2026-10-27
Feature: Manifest and Boost mirror stay in sync with the components
  Scenario: Success — nothing drifted
    When I run "ui:skill --check"
    Then the exit code is 0

  Scenario: Error — a prop changed without rebuilding
    Given a component gained a new prop
    When I run "ui:skill --check"
    Then the exit code is 1 and "resources/ui-manifest.json" is listed

  Scenario: Deviation — mirror edited by hand
    Given the Boost mirror differs from "skill/"
    When I run "ui:skill --check"
    Then the exit code is 1 and the mirror file is listed
