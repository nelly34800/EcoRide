<?php
function addUser(PDO $pdo, string $pseudo, string $email, string $password, string $last_name, string $first_name, string $address, int $role, string|null $image): bool
{
    $query = $pdo->prepare("INSERT INTO users (id, pseudo, email, password, last_name, first_name, address, role_id, image)
       VALUES (NULL, :pseudo, :email, :password, :last_name, :first_name, :address, :role_id, :image)");

    $password = password_hash($password, PASSWORD_DEFAULT);

    $query->bindValue(':pseudo', $pseudo);
    $query->bindValue(':email', $email);
    $query->bindValue(':password', $password);
    $query->bindValue(':last_name', $last_name);
    $query->bindValue(':first_name', $first_name);
    $query->bindValue(':address', $address);
    $query->bindValue(':role_id', $role, PDO::PARAM_INT);
    $query->bindValue(':image', $image);

    return $query->execute();
}

function verifyUser($user): array|bool
{
    $errors = [];
    if (isset($user["pseudo"])) {
        if ($user["pseudo"] === "") {
            $errors["pseudo"] = "Le champ pseudo est obligatoire";
        }
    } else {
        $errors["pseudo"] = "Le champ pseudo n'a pas été envoyé";
    }
    if (isset($user["email"])) {
        if ($user["email"] === "") {
            $errors["email"] = "Le champ email est obligatoire";
        } else {
            if (!filter_var($user["email"], FILTER_VALIDATE_EMAIL)) {
                $errors["email"] = "Le format d'email n'est pas respecté";
            }
        }
    } else {
        $errors["email"] = "Le champ email n'a pas été envoyé";
    }
    if (isset($user["password"])) {
        // Vérifie la longueur du mot de passe
        if (strlen($user["password"]) < 8) {
            $errors["password"] = "Le champ mot de passe doit faire au moins 8 caractères";
        } else {
            // Vérifie la présence d'une minuscule, d'une majuscule, d'un chiffre et d'un caractère spécial
            if (!preg_match('/[a-z]/', $user["password"])) {
                $errors["password"] = "Le mot de passe doit contenir au moins une minuscule";
            } elseif (!preg_match('/[A-Z]/', $user["password"])) {
                $errors["password"] = "Le mot de passe doit contenir au moins une majuscule";
            } elseif (!preg_match('/[0-9]/', $user["password"])) {
                $errors["password"] = "Le mot de passe doit contenir au moins un chiffre";
            } elseif (!preg_match('/[^a-zA-Z0-9]/', $user["password"])) {
                $errors["password"] = "Le mot de passe doit contenir au moins un caractère spécial";
            }
        }
    } else {
        $errors["password"] = "Le champ mot de passe n'a pas été envoyé";
    }
    if (isset($user["last_name"])) {
        if ($user["last_name"] === "") {
            $errors["last_name"] = "Le champ nom est obligatoire";
        }
    } else {
        $errors["last_name"] = "Le champ nom n'a pas été envoyé";
    }
    if (isset($user["first_name"])) {
        if ($user["first_name"] === "") {
            $errors["first_name"] = "Le champ prénom est obligatoire";
        }
    } else {
        $errors["first_name"] = "Le champ prénom n'a pas été envoyé";
    }
    if (isset($user["address"])) {
        if ($user["address"] === "") {
            $errors["address"] = "Le champ adresse est obligatoire";
        }
    } else {
        $errors["address"] = "Le champ adresse n'a pas été envoyé";
    }

    if (count($errors)) {
        return $errors;
    } else {
        return true;
    }
}
function verifyUserLoginPassword(PDO $pdo, string $email, string $password): bool|array
{
    $query = $pdo->prepare("SELECT id, pseudo, email, password FROM users WHERE email = :email");
    $query->bindValue(":email", $email);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user["password"])) {
        return $user;
    } else {
        return false;
    }
}
