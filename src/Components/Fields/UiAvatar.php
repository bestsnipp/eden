<?php

namespace BestSnipp\Eden\Components\Fields;

/**
 * @method static static make(mixed $name = 'Avatar', string $key = 'name')
 */
class UiAvatar extends Text
{
    public $visibilityOnUpdate = false;

    public $visibilityOnCreate = false;

    protected $avatarStyle = 'circle';

    protected $size = 40;

    protected $settings = [];

    protected function __construct($title = null, $key = null)
    {
        if (is_null($title)) {
            $title = 'Avatar';
        }
        if (is_null($key)) {
            $key = 'name';
        }
        parent::__construct($title, $key);
    }

    public function styleRounded()
    {
        $this->avatarStyle = 'rounded';

        return $this;
    }

    public function styleSquare()
    {
        $this->avatarStyle = 'square';

        return $this;
    }

    public function styleCircle()
    {
        $this->avatarStyle = 'circle';

        return $this;
    }

    public function size($size = 40)
    {
        $this->size = $size;

        return $this;
    }

    public function withSettings(array $settings = [])
    {
        $this->settings = $settings;

        return $this;
    }

    public function view()
    {
        return '';
    }

    public function viewForIndex()
    {
        parent::viewForIndex();

        return view('eden::fields.row.ui-avatar')
            ->with('uiAvatarUrl', $this->generateUiAvatarUrl())
            ->with('size', $this->size)
            ->with('avatarStyle', [
                'circle' => 'rounded-full',
                'rounded' => 'rounded-lg',
                'square' => '',
            ][$this->avatarStyle]);
    }

    public function viewForRead()
    {
        parent::viewForRead();

        return view('eden::fields.view.ui-avatar')
            ->with('uiAvatarUrl', $this->generateUiAvatarUrl())
            ->with('size', $this->size)
            ->with('avatarStyle', [
                'circle' => 'rounded-full',
                'rounded' => 'rounded-lg',
                'square' => '',
            ][$this->avatarStyle]);
    }

    protected function generateUiAvatarUrl()
    {
        return 'https://ui-avatars.com/api/?'.http_build_query([
            'name' => $this->value,
            'size' => $this->size,
            ...$this->settings,
        ]);
    }
}
