<?php


namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use App\Services\SaveAppointment;
use App\Interfaces\AppointmentSaverInterface;   
use App\Data\AppointmentData;
use App\Data\AppointmentResult;

class SaveAppointmentTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testSaveAppointments()
    {
        // テスト用の AppointmentData を作成
        $appointmentData = new AppointmentData(
            itemIds: [3, 2],
            customerId: 1,
            staffId: 2,
            appointmentDate: '2031-03-26',
            appointmentTime: '14:22'
        );

        // AppointmentSaverInterface のモックを作成
        $appointmentSaverMock = Mockery::mock(AppointmentSaverInterface::class);

        // saveAppointments メソッドが期待する値を返すように設定
        $appointmentSaverMock->shouldReceive('saveAppointments')
                        ->once()
                        ->with($appointmentData)
                        ->andReturn(new AppointmentResult([]));

        
        //モックを使って SaveAppointment インスタンスを作成
        $saveAppointment = new SaveAppointment($appointmentSaverMock);

        // saveAppointments メソッドを実行し、結果を検証
        $result = $saveAppointment->saveAppointments($appointmentData);

        // 返された結果が AppointmentResult インスタンスであることを確認
        $this->assertInstanceOf(AppointmentResult::class, $result);
    }
}



   





