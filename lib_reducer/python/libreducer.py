#!/usr/bin/python26
import sys
from abc import ABCMeta,abstractmethod

class BaseReducer:
	__metaclass__ = ABCMeta
	
	def setup(self):
		pass
		
	def cleanup(self):
		pass
		
	def parse(self, line):
		kv = line.split("\t", 1)
		key = kv[0]
		value = "" if len(kv) == 1 else kv[1]
		return (key, value)
	
	@abstractmethod	
	def reduce(self, key, values):
		pass

class ValuesIterator:
	def __init__(self, reducer):
		self.current_key = "\t"
		self.last_key = "\t"
		self.is_eof = False
		self.is_newline = False
		self.init = False
		self.reducer = reducer
		
	def next_key(self):
		if not self.is_newline:
			while True:
				try: self.next()
				except StopIteration:
					if self.is_eof:
						return False
					else:
						self.is_newline = False
						return True
				if self.last_key != self.current_key:
					self.is_newline = False
					return True
		else: return True
			
	
	def __iter__(self):
		self.last_key = self.current_key
		self.is_newline = False
		self.init = True
		return self
		
	def next(self):
		if self.init:
			self.init = False
			return self.current_value
		while True:
			line = sys.stdin.readline()
			if line == "":
				self.is_eof = True
				raise StopIteration
			line = line.strip()
			if line != "": break		
		self.last_key = self.current_key
		(self.current_key, self.current_value) = self.reducer.parse(line)
		if self.last_key != "\t" and self.last_key != self.current_key:	
			self.is_newline = True
			raise StopIteration
		return self.current_value
		
		
def run(reducer):	
	reducer.setup()	
	values = ValuesIterator(reducer)
	while True:
		if not values.next_key(): break
		reducer.reduce(values.current_key, values)
	reducer.cleanup()
	
