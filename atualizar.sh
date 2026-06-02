# criar Branch de desenvolvimento
current_branch=$(git rev-parse --abbrev-ref HEAD)


# echo "Digite o nome da atualização: "
# read atualizacao

hora_atual=$(date +"%Y-%m-%d %H:%M:%S")

git fetch --prune
git add .
git commit -m "Updated $hora_atual"
git fetch origin $current_branch
git merge --no-ff -m "Merge branch 'origin/$current_branch' into $current_branch" origin/$current_branch
git pull origin $current_branch
git branch -M $current_branch
git push -u origin $current_branch

if [[ $current_branch == "main" ]]; then
    echo ""
    # bash .server-update.sh
else
    echo "Você está em uma branch de desenvolvimento, não será feito o deploy no servidor."
fi

