function remove_module() {
  module="${1}"
  echo $module
  cd "./application/controllers/admin/"
  controller="$module.php"

  if [ -e $controller ]; then
    rm -rf $controller
    echo "delete controller "$controller
  fi

  cd "../../../application/models/"
  model=$module"_model.php"
  if [ -e $model ]; then
    rm -rf $model
    echo "Delete module "$module
  fi

  name_new=${module,,}

  cd "../../application/views/admin/"
  if [ -d "$name_new" ]; then
    rm -rf "$name_new"
    echo "Delete view "$name_new
  fi

  cd "../../../public/admin/js/pages/"
  js="$name_new.js"
  if [ -e $js ]; then
    rm -rf $js
    echo "Delete $name_new.js"
  fi
  cd "../../../../"
}

function list_table() {

  module=$1
  module=${module,,}

  table="DROP TABLE IF EXISTS ap_"$module";"
  table_trans="DROP TABLE IF EXISTS ap_"$module"_translations;"
  table_cat="DROP TABLE IF EXISTS ap_"$module"_category;"
  table_relations="DROP TABLE IF EXISTS ap_"$module"_relations;"
  table_prop="DROP TABLE IF EXISTS ap_"$module"_property;"
  tables="$table $table_trans  $table_cat $table_relations $table_prop"
  echo "$tables"
}

##########################
#Xu ly xoa module va database

function delete_module() {
  #  echo "Delete module"
  #  read -p "name: " module
  module="${1}"
  echo "cac module muon xoa: $module"
  listTables=""
  for md in $module; do
    remove_module "$md"
    table=$(list_table "$md")
    listTables=$listTables" $table"

  done
  echo "$listTables"
  read -p "Nhap ten database: " dbnane
  mysql -u root -p $dbnane <<EOFMYSQL
    $listTables;
EOFMYSQL
  echo "Finish"
}

##########################
# xy ly lay toan bo controller
cd "./application/controllers/admin/"
listController=""
for file in *".php"; do

  arrIgnone=("Translate" "Auth" "Category" "Dashboard" "Media" "Setting" "System_menu" "Users" "Logaction" "Template_mail")

  NAME=$(basename "$file" | cut -d'.' -f-1)
  if [[ ! " ${arrIgnone[@]} " =~ " ${NAME} " ]]; then
    listController=$listController" $NAME"
  fi
done
cd "../../../"
#########################
#########################

options=($listController)
menu() {
  echo "Chon module muon xoa:"
  for i in ${!options[@]}; do
    printf "%3d%s) %s\n" $((i + 1)) "${choices[i]:- }" "${options[i]}"
  done
  if [[ "$smg" ]]; then echo "$smg"; fi
}

prompt="chon 1 module de xoa (chon 1 lan nua de bo chon, ENTER de hoan tat): "
while menu && read -rp "$prompt" num && [[ "$num" ]]; do
  [[ "$num" != *[![:digit:]]* ]] &&
    ((num > 0 && num <= ${#options[@]})) ||
    {
      smg="Invalid option: $num"
      continue
    }
  ((num--))
  [[ "${choices[num]}" ]] && choices[num]="" || choices[num]="+"
done

smg=" nothing"
listChoose=""
for i in ${!options[@]}; do
  [[ "${choices[i]}" ]] && {
    smg=""
    listChoose=$listChoose" ${options[i]}"
  }
done
read -p "Ban co chac chan muon xoa khong (y/n)? " reply
if [ "$reply" = "y" ]; then
  delete_module "$listChoose"
fi
#########################
#sh rm_file.sh
