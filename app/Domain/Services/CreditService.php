<?php

use App\Domain\Interfaces\CreditRepositoryInterface;
use App\Http\Requests\CreateRequestCredit;

class CreditService
{
    protected $creditRepository;

    public function __construct(CreditRepositoryInterface $creditRepository)
    {
        $this->creditRepository = $creditRepository;
    }

    public function createRequestCredit(CreateRequestCredit $createRequestCredit,array $product) {
        return $this->creditRepository->createRequestCredit($createRequestCredit,$product);
    }

    public function getAllCredits(): Collection
    {
        return $this->creditRepository->all();
    }

    public function getCreditById(int $id): ?Credit
    {
        return $this->creditRepository->findById($id);
    }

    public function createCredit(array $data): Credit
    {
        return $this->creditRepository->create($data);
    }

    public function updateCredit(int $id, array $data): bool
    {
        return $this->creditRepository->update($id, $data);
    }

    public function deleteCredit(int $id): bool
    {
        return $this->creditRepository->delete($id);
    }
}
