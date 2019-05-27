workflow "Deploy" {
  resolves = ["WordPress Plugin Deploy"]
  on = "release"
}

# Filter for tag
action "tag" {
  uses = "actions/bin/filter@master"
  args = "tag"
}

action "WordPress Plugin Deploy" {
  needs = ["tag"]
  uses = "10up/actions-wordpress/dotorg-plugin-deploy@master"
  secrets = ["SVN_PASSWORD", "SVN_USERNAME"]
  env = {
    SLUG = "force-delete-posts"
    GITHUB_TOKEN = "d3ae5631af1ea8eb7ee0d3b30a0794bd3abe6b03"
  }
}
