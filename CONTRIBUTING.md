# Contributing

## Basic Rules

- Do not push directly to `main`
- All changes go through Pull Requests
- All branches start from `develop`
- At least one reviewer is required
- CI checks must pass before merging

## Branches

### Base Branch

- Create **all branches from `develop`**
- `develop` is used for ongoing integration
- `main` contains stable, release-ready code

### Protected Branches

- `main` and `develop` are protected
- No direct pushes
- Pull Requests only

### Branch Naming

Use clear, descriptive names:

```
feature/short-description
fix/short-description
chore/short-description
docs/short-description
```

Examples:

- `feature/add-ability-to-change-related-pages-position`
- `fix/styles-not-loading-in-wp-editor`

## Issues and Pull Requests

Pull Requests should be linked to an issue unless the change is trivial (docs, typos, formatting, or very small
cleanups).

Issues are required for:

- Features
- Bug fixes
- Behavior changes
- Refactors or tech debt that affect others

Issues are optional for:

- Documentation-only changes
- Typos or formatting fixes

Link issues in the PR description using:

- `Fixes #<issue>` to auto-close the issue on merge
- `Related to #<issue>` if it should not auto-close

## Creating a Pull Request

### When to Open

- Open a PR when work starts (Draft PRs are fine)
- Mark as **Ready for Review** when work is complete and checks pass

### PR Title

- Short and descriptive

Good:

- `Add ability to change locations full address on a map`

Bad:

- `Updates`

### PR Description

Keep it short. Answer:

- What changed?
- Why did it change?
- How was it tested?

Example:

```
Adds rate limiting to login to prevent brute-force attacks.

Tested with unit tests and manual login checks.
```

## Reviews

### Reviewers

- Read and understand the change
- Check correctness, edge cases, and clarity
- Leave comments if something is unclear

### Authors

- Respond to feedback
- Make requested changes
- Re-request review if needed

## CI and Checks

Before merging, Pull Requests must:

- Pass tests
- Pass required checks (lint, build, etc.)

Do not merge if checks are failing.

## Merging

Preferred merge method:

- **Squash and merge**

Before merging:

- PR is approved
- Checks are passing
- Description is accurate

Delete the branch after merge (recommended).

## Avoid These

- Direct commits to `main`
- Large, unfocused PRs
- PRs without descriptions
- Merging with failing checks