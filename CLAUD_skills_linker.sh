mkdir -p .claude/skills
for d in .agents/skills/*/; do
  name=$(basename "$d")
  ln -sfn "../../.agents/skills/$name" ".claude/skills/$name"
done