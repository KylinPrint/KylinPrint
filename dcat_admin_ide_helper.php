<?php

/**
 * A helper file for Dcat Admin, to provide autocomplete information to your IDE
 *
 * This file should not be included in your code, only analyzed by your IDE!
 *
 * @author jqh <841324345@qq.com>
 */
namespace Dcat\Admin {
    use Illuminate\Support\Collection;

    /**
     * @property Grid\Column|Collection disableExport
     * @property Grid\Column|Collection checked
     * @property Grid\Column|Collection release_date
     * @property Grid\Column|Collection onsale
     * @property Grid\Column|Collection network
     * @property Grid\Column|Collection duplex
     * @property Grid\Column|Collection pagesize
     * @property Grid\Column|Collection binds
     * @property Grid\Column|Collection detail
     * @property Grid\Column|Collection id
     * @property Grid\Column|Collection sys
     * @property Grid\Column|Collection soc
     * @property Grid\Column|Collection binds_id
     * @property Grid\Column|Collection created_at
     * @property Grid\Column|Collection updated_at
     * @property Grid\Column|Collection name
     * @property Grid\Column|Collection type
     * @property Grid\Column|Collection version
     * @property Grid\Column|Collection is_enabled
     * @property Grid\Column|Collection parent_id
     * @property Grid\Column|Collection order
     * @property Grid\Column|Collection icon
     * @property Grid\Column|Collection uri
     * @property Grid\Column|Collection extension
     * @property Grid\Column|Collection permission
     * @property Grid\Column|Collection user_id
     * @property Grid\Column|Collection path
     * @property Grid\Column|Collection method
     * @property Grid\Column|Collection ip
     * @property Grid\Column|Collection input
     * @property Grid\Column|Collection permission_id
     * @property Grid\Column|Collection menu_id
     * @property Grid\Column|Collection role_id
     * @property Grid\Column|Collection slug
     * @property Grid\Column|Collection value
     * @property Grid\Column|Collection username
     * @property Grid\Column|Collection password
     * @property Grid\Column|Collection avatar
     * @property Grid\Column|Collection remember_token
     * @property Grid\Column|Collection printers_id
     * @property Grid\Column|Collection solutions_id
     * @property Grid\Column|Collection adapter
     * @property Grid\Column|Collection name_en
     * @property Grid\Column|Collection manufactors_id
     * @property Grid\Column|Collection uuid
     * @property Grid\Column|Collection connection
     * @property Grid\Column|Collection queue
     * @property Grid\Column|Collection payload
     * @property Grid\Column|Collection exception
     * @property Grid\Column|Collection failed_at
     * @property Grid\Column|Collection industry_tags_id
     * @property Grid\Column|Collection isconnected
     * @property Grid\Column|Collection email
     * @property Grid\Column|Collection token
     * @property Grid\Column|Collection brands_id
     * @property Grid\Column|Collection principle_tags_id
     * @property Grid\Column|Collection adapter_status
     * @property Grid\Column|Collection project_tags_id
     * @property Grid\Column|Collection note
     * @property Grid\Column|Collection comment
     * @property Grid\Column|Collection source
     * @property Grid\Column|Collection amd64
     * @property Grid\Column|Collection arm64
     * @property Grid\Column|Collection mips64el
     * @property Grid\Column|Collection loongarch64
     * @property Grid\Column|Collection email_verified_at
     *
     * @method Grid\Column|Collection disableExport(string $label = null)
     * @method Grid\Column|Collection checked(string $label = null)
     * @method Grid\Column|Collection release_date(string $label = null)
     * @method Grid\Column|Collection onsale(string $label = null)
     * @method Grid\Column|Collection network(string $label = null)
     * @method Grid\Column|Collection duplex(string $label = null)
     * @method Grid\Column|Collection pagesize(string $label = null)
     * @method Grid\Column|Collection binds(string $label = null)
     * @method Grid\Column|Collection detail(string $label = null)
     * @method Grid\Column|Collection id(string $label = null)
     * @method Grid\Column|Collection sys(string $label = null)
     * @method Grid\Column|Collection soc(string $label = null)
     * @method Grid\Column|Collection binds_id(string $label = null)
     * @method Grid\Column|Collection created_at(string $label = null)
     * @method Grid\Column|Collection updated_at(string $label = null)
     * @method Grid\Column|Collection name(string $label = null)
     * @method Grid\Column|Collection type(string $label = null)
     * @method Grid\Column|Collection version(string $label = null)
     * @method Grid\Column|Collection is_enabled(string $label = null)
     * @method Grid\Column|Collection parent_id(string $label = null)
     * @method Grid\Column|Collection order(string $label = null)
     * @method Grid\Column|Collection icon(string $label = null)
     * @method Grid\Column|Collection uri(string $label = null)
     * @method Grid\Column|Collection extension(string $label = null)
     * @method Grid\Column|Collection permission(string $label = null)
     * @method Grid\Column|Collection user_id(string $label = null)
     * @method Grid\Column|Collection path(string $label = null)
     * @method Grid\Column|Collection method(string $label = null)
     * @method Grid\Column|Collection ip(string $label = null)
     * @method Grid\Column|Collection input(string $label = null)
     * @method Grid\Column|Collection permission_id(string $label = null)
     * @method Grid\Column|Collection menu_id(string $label = null)
     * @method Grid\Column|Collection role_id(string $label = null)
     * @method Grid\Column|Collection slug(string $label = null)
     * @method Grid\Column|Collection value(string $label = null)
     * @method Grid\Column|Collection username(string $label = null)
     * @method Grid\Column|Collection password(string $label = null)
     * @method Grid\Column|Collection avatar(string $label = null)
     * @method Grid\Column|Collection remember_token(string $label = null)
     * @method Grid\Column|Collection printers_id(string $label = null)
     * @method Grid\Column|Collection solutions_id(string $label = null)
     * @method Grid\Column|Collection adapter(string $label = null)
     * @method Grid\Column|Collection name_en(string $label = null)
     * @method Grid\Column|Collection manufactors_id(string $label = null)
     * @method Grid\Column|Collection uuid(string $label = null)
     * @method Grid\Column|Collection connection(string $label = null)
     * @method Grid\Column|Collection queue(string $label = null)
     * @method Grid\Column|Collection payload(string $label = null)
     * @method Grid\Column|Collection exception(string $label = null)
     * @method Grid\Column|Collection failed_at(string $label = null)
     * @method Grid\Column|Collection industry_tags_id(string $label = null)
     * @method Grid\Column|Collection isconnected(string $label = null)
     * @method Grid\Column|Collection email(string $label = null)
     * @method Grid\Column|Collection token(string $label = null)
     * @method Grid\Column|Collection brands_id(string $label = null)
     * @method Grid\Column|Collection principle_tags_id(string $label = null)
     * @method Grid\Column|Collection adapter_status(string $label = null)
     * @method Grid\Column|Collection project_tags_id(string $label = null)
     * @method Grid\Column|Collection note(string $label = null)
     * @method Grid\Column|Collection comment(string $label = null)
     * @method Grid\Column|Collection source(string $label = null)
     * @method Grid\Column|Collection amd64(string $label = null)
     * @method Grid\Column|Collection arm64(string $label = null)
     * @method Grid\Column|Collection mips64el(string $label = null)
     * @method Grid\Column|Collection loongarch64(string $label = null)
     * @method Grid\Column|Collection email_verified_at(string $label = null)
     */
    class Grid {}

    class MiniGrid extends Grid {}

    /**
     * @property Show\Field|Collection disableExport
     * @property Show\Field|Collection checked
     * @property Show\Field|Collection release_date
     * @property Show\Field|Collection onsale
     * @property Show\Field|Collection network
     * @property Show\Field|Collection duplex
     * @property Show\Field|Collection pagesize
     * @property Show\Field|Collection binds
     * @property Show\Field|Collection detail
     * @property Show\Field|Collection id
     * @property Show\Field|Collection sys
     * @property Show\Field|Collection soc
     * @property Show\Field|Collection binds_id
     * @property Show\Field|Collection created_at
     * @property Show\Field|Collection updated_at
     * @property Show\Field|Collection name
     * @property Show\Field|Collection type
     * @property Show\Field|Collection version
     * @property Show\Field|Collection is_enabled
     * @property Show\Field|Collection parent_id
     * @property Show\Field|Collection order
     * @property Show\Field|Collection icon
     * @property Show\Field|Collection uri
     * @property Show\Field|Collection extension
     * @property Show\Field|Collection permission
     * @property Show\Field|Collection user_id
     * @property Show\Field|Collection path
     * @property Show\Field|Collection method
     * @property Show\Field|Collection ip
     * @property Show\Field|Collection input
     * @property Show\Field|Collection permission_id
     * @property Show\Field|Collection menu_id
     * @property Show\Field|Collection role_id
     * @property Show\Field|Collection slug
     * @property Show\Field|Collection value
     * @property Show\Field|Collection username
     * @property Show\Field|Collection password
     * @property Show\Field|Collection avatar
     * @property Show\Field|Collection remember_token
     * @property Show\Field|Collection printers_id
     * @property Show\Field|Collection solutions_id
     * @property Show\Field|Collection adapter
     * @property Show\Field|Collection name_en
     * @property Show\Field|Collection manufactors_id
     * @property Show\Field|Collection uuid
     * @property Show\Field|Collection connection
     * @property Show\Field|Collection queue
     * @property Show\Field|Collection payload
     * @property Show\Field|Collection exception
     * @property Show\Field|Collection failed_at
     * @property Show\Field|Collection industry_tags_id
     * @property Show\Field|Collection isconnected
     * @property Show\Field|Collection email
     * @property Show\Field|Collection token
     * @property Show\Field|Collection brands_id
     * @property Show\Field|Collection principle_tags_id
     * @property Show\Field|Collection adapter_status
     * @property Show\Field|Collection project_tags_id
     * @property Show\Field|Collection note
     * @property Show\Field|Collection comment
     * @property Show\Field|Collection source
     * @property Show\Field|Collection amd64
     * @property Show\Field|Collection arm64
     * @property Show\Field|Collection mips64el
     * @property Show\Field|Collection loongarch64
     * @property Show\Field|Collection email_verified_at
     *
     * @method Show\Field|Collection disableExport(string $label = null)
     * @method Show\Field|Collection checked(string $label = null)
     * @method Show\Field|Collection release_date(string $label = null)
     * @method Show\Field|Collection onsale(string $label = null)
     * @method Show\Field|Collection network(string $label = null)
     * @method Show\Field|Collection duplex(string $label = null)
     * @method Show\Field|Collection pagesize(string $label = null)
     * @method Show\Field|Collection binds(string $label = null)
     * @method Show\Field|Collection detail(string $label = null)
     * @method Show\Field|Collection id(string $label = null)
     * @method Show\Field|Collection sys(string $label = null)
     * @method Show\Field|Collection soc(string $label = null)
     * @method Show\Field|Collection binds_id(string $label = null)
     * @method Show\Field|Collection created_at(string $label = null)
     * @method Show\Field|Collection updated_at(string $label = null)
     * @method Show\Field|Collection name(string $label = null)
     * @method Show\Field|Collection type(string $label = null)
     * @method Show\Field|Collection version(string $label = null)
     * @method Show\Field|Collection is_enabled(string $label = null)
     * @method Show\Field|Collection parent_id(string $label = null)
     * @method Show\Field|Collection order(string $label = null)
     * @method Show\Field|Collection icon(string $label = null)
     * @method Show\Field|Collection uri(string $label = null)
     * @method Show\Field|Collection extension(string $label = null)
     * @method Show\Field|Collection permission(string $label = null)
     * @method Show\Field|Collection user_id(string $label = null)
     * @method Show\Field|Collection path(string $label = null)
     * @method Show\Field|Collection method(string $label = null)
     * @method Show\Field|Collection ip(string $label = null)
     * @method Show\Field|Collection input(string $label = null)
     * @method Show\Field|Collection permission_id(string $label = null)
     * @method Show\Field|Collection menu_id(string $label = null)
     * @method Show\Field|Collection role_id(string $label = null)
     * @method Show\Field|Collection slug(string $label = null)
     * @method Show\Field|Collection value(string $label = null)
     * @method Show\Field|Collection username(string $label = null)
     * @method Show\Field|Collection password(string $label = null)
     * @method Show\Field|Collection avatar(string $label = null)
     * @method Show\Field|Collection remember_token(string $label = null)
     * @method Show\Field|Collection printers_id(string $label = null)
     * @method Show\Field|Collection solutions_id(string $label = null)
     * @method Show\Field|Collection adapter(string $label = null)
     * @method Show\Field|Collection name_en(string $label = null)
     * @method Show\Field|Collection manufactors_id(string $label = null)
     * @method Show\Field|Collection uuid(string $label = null)
     * @method Show\Field|Collection connection(string $label = null)
     * @method Show\Field|Collection queue(string $label = null)
     * @method Show\Field|Collection payload(string $label = null)
     * @method Show\Field|Collection exception(string $label = null)
     * @method Show\Field|Collection failed_at(string $label = null)
     * @method Show\Field|Collection industry_tags_id(string $label = null)
     * @method Show\Field|Collection isconnected(string $label = null)
     * @method Show\Field|Collection email(string $label = null)
     * @method Show\Field|Collection token(string $label = null)
     * @method Show\Field|Collection brands_id(string $label = null)
     * @method Show\Field|Collection principle_tags_id(string $label = null)
     * @method Show\Field|Collection adapter_status(string $label = null)
     * @method Show\Field|Collection project_tags_id(string $label = null)
     * @method Show\Field|Collection note(string $label = null)
     * @method Show\Field|Collection comment(string $label = null)
     * @method Show\Field|Collection source(string $label = null)
     * @method Show\Field|Collection amd64(string $label = null)
     * @method Show\Field|Collection arm64(string $label = null)
     * @method Show\Field|Collection mips64el(string $label = null)
     * @method Show\Field|Collection loongarch64(string $label = null)
     * @method Show\Field|Collection email_verified_at(string $label = null)
     */
    class Show {}

    /**
     
     */
    class Form {}

}

namespace Dcat\Admin\Grid {
    /**
     
     */
    class Column {}

    /**
     
     */
    class Filter {}
}

namespace Dcat\Admin\Show {
    /**
     
     */
    class Field {}
}