<?php

// override core en language system validation or define your own en language validation message
return [
    'success' => [
        'userCreated' => 'Uživatel {name} byl úspěšně vytvořen.',
        'userUpdated' => 'Uživatel {name} byl upraven.',
        'recordCreated' => 'Záznam byl úspěšně vložen do databáze',
        'recordUpdated' => 'Záznam s id {id} byl úspěšně upraven',
        'recordDeleted' => 'Záznam s id {id} byl úspěšně smazán',
    ],

    'danger' => [
        'loginFailed' => 'Neplatné přihlašovací údaje.',
        'saveFailed'  => 'Uložení se nezdařilo.',
        'receordCreated' => 'Záznam se nepodařilo uložit do databáze',
        'recordUpdated' => 'Záznam s id {id} se nepodařilo upravit',
        'recordDeleted' => 'Záznam s id {id} se nepodařilo smazat',
    ],

    'warning' => [
        'notAllowed' => 'K této akci nemáte oprávnění.',
    ],

    'info' => [
        'nothingChanged' => 'Nebyly provedeny žádné změny.',
    ],
];
    