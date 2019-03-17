<?php declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Crypt;

/**
 * @mixin BaseModel
 */
trait HasEncryptedFields
{
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getAttribute($key)
    {
        if ($this->isKeyNotInEncryptedFields($key)) {
            return parent::getAttribute($key);
        }

        $encryptedValue = parent::getAttribute($key);
        if ($encryptedValue === null) {
            return null;
        }

        return $this->getValueWithoutSalt(Crypt::decrypt($encryptedValue));
    }

    private function isKeyNotInEncryptedFields(string $key): bool
    {
        return !$this->isKeyInEncryptedFields($key);
    }

    private function isKeyInEncryptedFields(string $key): bool
    {
        return in_array($key, $this->getEncryptedFields(), true);
    }

    abstract protected function getEncryptedFields(): array;

    public function getValueWithoutSalt(string $value): string
    {
        $salt = $this->getEncrypterSalt();
        if (empty($salt)) {
            return $value;
        }
        if (strpos($value, $salt) === 0) {
            return substr($value, strlen($salt));
        }

        return $value;
    }

    protected function getEncrypterSalt(): string
    {
        return '';
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        if ($value === null || $this->isKeyNotInEncryptedFields($key)) {
            return parent::setAttribute($key, $value);
        }

        return parent::setAttribute($key, Crypt::encrypt($this->getValueWithSalt($value)));
    }

    public function getValueWithSalt(string $value): string
    {
        return $this->getEncrypterSalt() . $value;
    }
}
