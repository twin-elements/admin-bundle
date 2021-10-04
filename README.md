##Installation
```composer require twin-elements/admin-bundle```

in `/config/packages/routes.yaml` add
```
admin_dashboard:
    path: /admin/
    controller: TwinElements\AdminBundle\Controller\DashboardController::index
    methods: GET
    options: { i18n: false }
    
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

in `/config/packages/security.yaml ` add
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
3.Done

#### Creating a new super admin user

In the command line entre the command: 

``php bin/console te:admin:create_super_admin``

