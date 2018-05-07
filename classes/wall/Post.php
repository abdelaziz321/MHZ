<?php


class Post
{

    /**
     * Container of the post data
     *
     * @var array
     */
    private $data;

    /**
     * Path of the post's image
     *
     * @var string
     */
    private $imagePath = PATH . 'uploads' . DS . 'posts' . DS;

    /**
     * fill the data attribute
     *
     * @param array $data
     */
    function __construct($data = null)
    {
        $this->data = $data;
    }

    /**
     * create new post using data container
     *
     * @return bool
     */
    public function createPost()
    {
        $image = new UploadedFile('image');

        if (!$this->postValidator($image)) {
            return false;
        }

        $image->moveTo($this->imagePath);

        $db = DB::getInstance();

        $params = [
            ':caption'    => $this->data['caption'],
            ':image'      => $image->getNewFileName(),
            ':status'     => $this->data['status'],
            ':created_at' => date('Y-m-d H:i:s'),
            ':account_id' => $_SESSION['user'],
        ];

        // insert [caption, image, status, created_at, account_id] into the database
        $db->insert('INTO posts SET
                        caption     = :caption,
                        image       = :image,
                        status      = :status,
                        created_at  = :created_at,
                        account_id  = :account_id',
                    $params);

        if ($db->errors()) {
            $_SESSION['errors'][] = 'can\'t insert';
            return false;
        }
        return true;
    }

    /**
     * get all posts for a specific profile
     *
     * @param $id
     * @return array
     */
    public static function getAccountPosts($id)
    {
        $db = DB::getInstance();
        $db->select('* FROM posts WHERE account_id = :account_id', [
            ':account_id' => $id
        ]);

        return $db->results();
    }

    /**
     * Create a private post when user change his profile picture
     */
    public function createProfilePicturePost()
    {
        $db = DB::getInstance();

        $picture = basename($this->data['picturePath']);

        $params = [
            ':caption'    => $this->data['caption'],
            ':image'      => $picture,
            ':status'     => 2,
            ':created_at' => date('Y-m-d H:i:s'),
            ':account_id' => $_SESSION['user']
        ];

        copy($this->data['picturePath'], "{$this->imagePath}{$picture}");

        // insert [caption, image, status, created_at, account_id] into the database
        $db->insert('INTO posts SET
                        caption     = :caption,
                        image       = :image,
                        status      = :status,
                        created_at  = :created_at,
                        account_id  = :account_id',
                    $params);

        if ($db->errors()) {
            $_SESSION['errors'][] = 'can\'t insert';
            return false;
        }
    }


    /**
     * validating the [caption, user, image, status]
     * for the new post form
     *
     * @return bool
     */
    private function postValidator($image)
    {
        $validator = new Validator($this->data);

        if ($image->isExists()) {
            $validator->image('image');
        } else {
            $validator->required('caption');
        }

        if ($validator->fails()) {
            $_SESSION['errors'] = $validator->getMessages();
            return false;
        }
        return true;
    }

}
