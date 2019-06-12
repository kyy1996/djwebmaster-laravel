<?php

use App\Model\Menu;
use Illuminate\Database\Seeder;

class MenuTableSeeder extends Seeder
{
    protected $menus = [
        'main' => [
            [
                'title'       => '控制台概览',
                'url'         => '/',
                'sort'        => 0,
                'description' => '',
                'icon_class'  => 'dashboard',
            ],
            [
                'title'       => '部落格',
                'url'         => '/blog/article',
                'sort'        => 0,
                'description' => '',
                'icon_class'  => 'description',
                '_child'      => [
                    [
                        'title'       => '浏览',
                        'url'         => '/blog/article/',
                        'sort'        => 0,
                        'description' => '',
                        'icon_class'  => 'list',
                        '_child'      => [
                            [
                                'title'       => '查看',
                                'url'         => '/blog/article/show',
                                'sort'        => 0,
                                'description' => '',
                                'icon_class'  => 'visibility',
                            ],
                            [
                                'title'       => '删除',
                                'url'         => '/blog/article/delete',
                                'sort'        => 0,
                                'description' => '',
                                'icon_class'  => '',
                            ],
                            [
                                'title'       => '保存',
                                'url'         => '/blog/article/update',
                                'sort'        => 0,
                                'description' => '',
                                'icon_class'  => '',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'title'       => '用户管理',
                'url'         => '/user/user',
                'sort'        => 0,
                'description' => '',
                'icon_class'  => 'person',
                '_child'      => [
                    [
                        'title'       => '浏览',
                        'url'         => '/user/user/',
                        'sort'        => 0,
                        'description' => '',
                        'icon_class'  => 'list',
                    ],
                    [
                        'title'       => '个人资料',
                        'url'         => '/user/user/my',
                        'sort'        => 0,
                        'description' => '',
                        'icon_class'  => 'description',
                    ],
                ],
            ],
            [
                'title'       => '操作记录',
                'url'         => '/user/log/',
                'sort'        => 0,
                'description' => '',
                'icon_class'  => 'mouse',
            ],
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Throwable
     */
    public function run(): void
    {
        foreach ($this->menus as $group => $menus) {
            $this->writeModels($menus, $group);
        }
    }

    /**
     * @param array[]              $menus
     * @param string               $group
     * @param \App\Model\Menu|null $parentModel
     * @throws \Throwable
     */
    private function writeModels(array $menus, string $group = 'main', $parentModel = null): void
    {
        foreach ($menus as $menu) {
            $model    = new Menu();
            $children = $menu['_child'] ?? [];
            unset($menu['_child']);
            $menu['group'] = $group;
            $model->fill($menu);
            $model->saveOrFail();
            if ($parentModel) {
                $parentModel->children()->save($model);
            }
            if ($children) {
                $this->writeModels($children, $group, $model);
            }
        }
    }
}
