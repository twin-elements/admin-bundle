##Installation

in `/config/packages/routes.yaml` add
```
admin_dashboard:
    path: /admin/
    controller: TwinElements\AdminBundle\Controller\DashboardController::index
    methods: GET

admin_core:
    resource: "@TwinElementsAdminBundle/Controller/"
    type: annotation
    prefix: /admin
    requirements:
        _locale: '%app_locales%'
    defaults:
        _locale: '%locale%'
        _admin_locale: '%admin_locale%'
    options: { i18n: false }
```

add in `/config/packages/security.yaml `
```
imports:
    - { resource: '@TwinElementsAdminBundle/Resources/config/security.yaml' }
```

#### How create a new Roles?

1.Create class implements `TwinElements\AdminBundle\Role\RoleGroupInterface`

2.Register class in services.yaml 
```
<service id="YOUR_CLASS">
    <tag name="twin_elements.role" priority="2-999"/>
</service>
```
3.Roles automatically implemented to roles list type
4.Add to messages.LANG.yaml translations as 
```
role:
    role_name: Role name
```

#### How create a new AdminMenu items
1.Create class implements `TwinElements\AdminBundle\Menu\AdminMenuInterface`

2.Register class in services.yaml 
```
<service id="YOUR_CLASS">
    <tag name="twin_elements.admin_menu"/>
</service>
```
3.Menu items automatically added in main admin menu

