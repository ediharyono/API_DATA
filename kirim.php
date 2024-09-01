//KIRIM KE DATA DEVELOPER//
public function kirim($tahun_angkatan)
//public function kirim($mahasiswa_kode)
	{
	    //Token_uwm_model_dev
		set_time_limit(0);
		$token = $this->tkn_dev->token_simak(); 
		$manual = array();
		//LIST DARI DATA//
		$this->db->where(
		       array('tahun_masuk'=> $tahun_angkatan,
		      'un_mahasiswa_id'=> '')
		     );
		 $query = $this->db->get('un_mahasiswa'); 	

		if( $query->num_rows() > 0) 
		{
			$result = $query->result(); //or $query->result_array() to get an array
			foreach( $result as $rows )
			{									
				$manual = 
				    array(
				        
				        'token'=>$token,
                        'pmb_reg_id' => null,
                        'un_kelompok_kelas_id' => $rows->un_kelompok_kelas_id,
                        'un_tahun_ajaran_id_masuk' => $rows->un_tahun_ajaran_id_masuk,
                        'un_prodi_id' => $rows->un_prodi_id,
                        'mahasiswa_kode' => $rows->mahasiswa_kode,
                        'kunci' => $rows->kunci,
                        'nama_lengkap' => $rows->nama_lengkap,
                        'flag_allow_login' => $rows->flag_allow_login,                
                        'spp_tetap' => $rows->spp_tetap,
                        'spp_variabel' => $rows->spp_variabel,
                        'spp_paket' => $rows->spp_paket,
                        'dana_pengembangan_akademik' => $rows->dana_pengembangan_akademik,
                        'dana_pengembangan_kampus' => $rows->dana_pengembangan_kampus,
                        'dana_jas_tas_ospek_ktm' => $rows->dana_jas_tas_ospek_ktm,
                        'dana_it' => $rows->dana_it,
                        'dana_kegiatan_mahasiswa' => $rows->dana_kegiatan_mahasiswa,
                        'dana_asuransi' => $rows->dana_asuransi,
                        'dana_administrasi_wna' => $rows->dana_administrasi_wna,
				        
                        "pmb_jenis_beasiswa_id"  => $rows->pmb_jenis_beasiswa_id,
                        "flag_beasiswa"  => $rows->flag_beasiswa,
                        "flag_wna"  => $rows->flag_wna,
                        "skema_bayar"  => $rows->skema_bayar,
				        
				        );
            				/////////////////// DATA JSON YANG DIKIRIM //////////////	
            				echo '>';
            				echo " " . json_encode($manual,true);
            				$data_manual = json_encode($manual,true);
				/////////////////////////////////////////////////////////
				//echo $data_manual;
						/////CURL///////////////////////////////////////
						///$this->load->library('Curl');	
						///developer
						$url = 'http://202.162.33.122:10001/un_mahasiswa/insert';						
						//$url = 'https://api.widyamataram.ac.id/un_mahasiswa/insert';
						$this->curl->create($url);
						$this->curl->option(CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_manual)));
						$this->curl->post($data_manual);
						$result_profil = $this->curl->execute();
						///////////////////////////////////////////////
						
							//////RESPON///// 
							$coba = json_decode($result_profil);
							$data_sync = json_encode($coba->kode,true);
							
							echo 'dataku ini '.$data_sync;
							
 							$data_response1 = json_encode($coba);
							//$data_response2 = json_encode($coba->data);
							
							echo 'response ini '. $data_response1;
							//echo $data_response2;
							////////////////

								///MENGELOLARESPON///
								if ($data_sync == '200')
										{
										    //UPDATE `un_mahasiswa` SET `un_mahasiswa_id` = 'c1a56854-7946-4871-af6f-f255c5c3bcca' WHERE `un_mahasiswa`.`mahasiswa_kode` = '161216400';
												//echo "Menginput response";
												$un_mahasiswa_id = str_replace('"','',json_encode($coba->data->un_mahasiswa_id,true));
												//$this->load->model('un_mahasiswa/un_mahasiswa_model_datatable','sk');	

                                                //echo $un_mahasiswa_id;
												$data = array('un_mahasiswa_id' => $un_mahasiswa_id);
 
												$this->sk->update(array
														(
															'mahasiswa_kode' => $rows->mahasiswa_kode
														), $data);
												
												
										} 
								else if ($data_sync == '1')
										{
											//echo "Gagal";
										}
								////////////////////////////
			}
			/////////////////////
			$this->curl->close();
			//CLOSE CURL seperti curl_close($ch);///				
			/////////////////////
		}	
 } 
////////////////////////
