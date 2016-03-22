from libreducer import BaseReducer, run

class MyReducer(BaseReducer):
	# def parse(self, line):
		# k, v = super(MyReducer, self).parser(line)
		# return (k+ "k", v+"v")
		
	def reduce(self, key, values):		
		for v in values:			
			print key + ":\t:" + v
			
				
	
run(MyReducer())